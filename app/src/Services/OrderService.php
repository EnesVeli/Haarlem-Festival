<?php
namespace App\Services;

use App\Enums\BookingType;
use App\Enums\OrderStatus;
use App\Framework\Session;
use App\Models\Exceptions\EmptyCartException;
use App\Models\Exceptions\EmptyPostException;
use App\Models\Exceptions\PostMismatchException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\History\HistoryBooking;
use App\Models\IBooking;
use App\Models\Jazz\JazzBooking;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoryBooking;
use App\Models\Ticket;
use App\Models\YummyBooking;
use App\Repositories\HistoryCmsRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\JazzRepository;
use App\Repositories\OrderRepository;
use App\Repositories\StoriesRepository;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use App\Repositories\YummyRestaurantsRepository;
use DateInterval;
use DateTime;
use Exception;
class OrderService
{
    public static int $VAT_RATE = 900;
    public static int $YUMMY_COST_PER_SEAT = 1000;
    public static int $HISTORY_ROUTE_DURATION = 180; // In munutes

    private UserRepository $user_rep;
    private OrderRepository $order_rep;
    private TicketRepository $ticket_rep;
    private HistoryCmsRepository $history_cms_rep;
    private YummyRestaurantsRepository $restaurant_rep;
    private StoriesRepository $story_rep;
    private HistoryRepository $history_rep;
    private JazzRepository $jazz_rep;
    private PdfService $pdf_service;
    private MailService $mail_service;

    public function __construct()
    {
        $this->user_rep = new UserRepository();
        $this->order_rep = new OrderRepository();
        $this->ticket_rep = new TicketRepository();
        $this->history_cms_rep = new HistoryCmsRepository();
        $this->restaurant_rep = new YummyRestaurantsRepository();
        $this->story_rep = new StoriesRepository();
        $this->history_rep = new HistoryRepository();
        $this->jazz_rep = new JazzRepository();
        $this->pdf_service = new PdfService();
        $this->mail_service = new MailService();
    }

    /**
     * Creates booking in db. And addes it to user cart.
     * @param int $user_id id of user
     * @param IBooking $booking booking to add
     * @throws QueryExecutionException if there where errors during with db.
     * @return void
     */
    public function createAndAddBookingToCart(int $user_id, IBooking $booking){   
        $booking_id = $this->addBooking($booking);      
        
        if($booking_id == null) throw new QueryExecutionException("Failed to create booking.");   

        $booking->setBookingId($booking_id);

        $this->addBookingToCart($user_id, $booking);
    }

    /**
     * Addes booking to users cart
     * @param int $user_id id of user
     * @param IBooking $booking booking to add
     * @throws QueryExecutionException if there where errors during with db.
     * @return void
     */
    public function addBookingToCart(int $user_id, IBooking $booking){     
        $order = $this->order_rep->getOrderByUserIdAndStatus($user_id, OrderStatus::InCart); // get cart order

        if($order == null){ // if there are no cart order for the user
            $order_id = $this->order_rep->createCartOrder($user_id); // create order cart order for the user

            if($order_id == null) throw new QueryExecutionException("Failed to create cart order.");       
        }
        else // if the cart order already exist
        {
            $order_id = $order->order_id; // take its id
        }

        // create order ite
        $item = new OrderItem();
        $item->order_id = $order_id;
        $item->booking_id = $booking->getBookingId();
        $item->booking_type = $booking->getBookingType();
        $item->price = $this->calcBookingPrice($booking);

        // add order item to db
        if($this->order_rep->createOrderItem($item) == null) throw new QueryExecutionException("Failed to create order item.");  

        Session::setCartItemsCount(Session::getCartItemsCount() + 1);
    }

    /**
     * Addes booking (uses different methods depending on booking type)
     * @param IBooking $booking booking to add.
     * @return ?int returns id of the new booking if operation was successfull, otherwise, returns null. Also returns null if booking type is unknown. 
     */
    public function addBooking(IBooking $booking) : ?int{
        switch($booking->getBookingType()){
            case BookingType::Yummy:
                return $this->order_rep->createYummyBooking($booking);
            case BookingType::History:
                return $this->order_rep->createHistoryBooking($booking);
            case BookingType::Stories:
                return $this->order_rep->createStoryBooking($booking);
            case BookingType::Jazz:
                return $this->order_rep->createJazzBooking($booking);
        }

        return null;
    }

    public function getHistoryIndividualPrice() : int {
        return $this->history_cms_rep->getIndividualPrice();
    }

    public function getHistoryFamilyPrice() : int {
        return $this->history_cms_rep->getFamilyPrice();
    }

    /**
     * Calculates price of a booking depending on its type.
     * @param IBooking $booking from which to calculate price
     * @return int returns calculated price.
     */
    public function calcBookingPrice(IBooking $booking) : int{
        switch($booking->getBookingType()){
            case BookingType::Yummy:
                $booking = (fn($booking):YummyBooking=>$booking)($booking);
                return ($booking->adult_number + $booking->child_number) * self::$YUMMY_COST_PER_SEAT;
            case BookingType::History:
                $booking = (fn($booking):HistoryBooking=>$booking)($booking);
                return $booking->individual_count * $this->getHistoryIndividualPrice() + $booking->family_count * $this->getHistoryFamilyPrice();
            case BookingType::Stories:
                $booking = (fn($booking):StoryBooking=>$booking)($booking);

                $event = $this->story_rep->getById($booking->event_id);
                if($event == null) throw new QueryExecutionException("Failed to get story event.");

                if($booking->pay_as_you_like != null) return $booking->pay_as_you_like;

                if($booking->haarlem_pass) return $booking->quantity * $event->price * 75 / 100;

                return $booking->quantity * $event->price;
            case BookingType::Jazz:
                $booking = (fn($booking):JazzBooking=>$booking)($booking);

                $perf = $this->jazz_rep->getPerformerById($booking->booking_id);
                if($perf == null) throw new QueryExecutionException("Failed to get jazz performer.");

                return $booking->amount * $perf->price;
        }

        return 0;
    }

    public function getOrderItemWithBooking(int $item_id) : ?OrderItem {
        $item = $this->order_rep->getOrderItemById($item_id);
        if($item === null) return null;
        if($item === false) throw new QueryExecutionException("Failed to get order item by id.");  

        $item->booking = $this->getBookingByIdAndType($item->booking_id, $item->booking_type);
        if($item->booking === null) throw new QueryExecutionException("Failed to get booking for order item.");  

        return $item;
    }

    /**
     * Get users cart order with order items (and bookings inside) filled in.
     * @param int $user_id id of the user.
     * @throws QueryExecutionException if errors during query execution.
     * @return Order|null If no order found, returns null. Otherwise, returns order.
     */
    public function getOrderWithOrderItemsByUserId(int $user_id, OrderStatus $status = OrderStatus::InCart) : ?Order{
        $order = $this->order_rep->getOrderByUserIdAndStatus($user_id, $status); // Get order

        if($order != null){
            $order_items = $this->order_rep->getOrderOrderItems($order->order_id);
            if($order_items === false) throw new QueryExecutionException("Failed to get order order_items.");  

            if($order_items === null) $order_items = [];
            
            foreach($order_items as $item){
                $booking = $this->getBookingByIdAndType($item->booking_id, $item->booking_type);
                if($booking == null) throw new QueryExecutionException("Failed to get booking.");  

                $item->booking = $booking;
            }

            $order->order_items = $order_items;
        }

        return $order;
    }

    /**
     * Gets booking by id and fills it with needed data depending on the type.
     * @param int $booking_id id of the booking.
     * @param BookingType $booking_type type of the booking.
     * @throws QueryExecutionException if any errors during query execution.
     * @return HistoryBooking|JazzBooking|StoryBooking|YummyBooking|null If booking type is not listed, returns null. Otherwise, returns booking.
     */
    public function getBookingByIdAndType(int $booking_id, BookingType $booking_type) : ?IBooking{
        switch($booking_type){
            case BookingType::Yummy:
                $book = $this->order_rep->getYummyBookingById($booking_id);

                if($book != null){
                    $book->reservation_time_slot = $this->restaurant_rep->getRestaurantTimeSlotById($book->reservation_id);
                    if($book->reservation_time_slot == null) throw new QueryExecutionException("Failed to get reservation time slot for yummy booking.");  

                    $book->restaurant = $this->restaurant_rep->getRestaurantById($book->reservation_time_slot->restaurant_id);
                    if($book->restaurant == null) throw new QueryExecutionException("Failed to get restaurant for yummy booking.");  
                }
                return $book;
            case BookingType::History:
                $book = $this->order_rep->getHistoryBookingById($booking_id);

                if($book != null){
                    $book->reservation = $this->history_rep->getHistoryReservationSlotById($book->reservation_id);
                    if($book->reservation == null) throw new QueryExecutionException("Failed to get reservation for history booking.");  

                    $book->time_slot = $this->history_rep->getHistoryTimeSlotById($book->reservation->slot_id);
                    if($book->reservation == null) throw new QueryExecutionException("Failed to get time slot for history booking.");  
                }
                return $book;
            case BookingType::Stories:
                $book = $this->order_rep->getStoryBookingById($booking_id);

                if($book != null){
                    $book->event = $this->story_rep->getById($book->event_id);
                    if($book->event == null) throw new QueryExecutionException("Failed to get story even for story booking.");   
                }

                return $book;
            case BookingType::Jazz:
                $book = $this->order_rep->getJazzBookingById($booking_id);

                $book->performer = $this->jazz_rep->getPerformerById($book->performer_id);
                if($book->performer == null) throw new QueryExecutionException("Failed to get jazz performer.");

                return $book;
        }

        return null;
    }

    /**
     * Calculates order subtotal (before Vat).
     * @param Order $order order with filled in order_items.
     * @return int returns subtotal as int.
     */
    public function calcOrderSubtotalPrice(Order $order) : int{
        $subtotal = 0;

        foreach($order->order_items as $item){
            $subtotal += $item->price;
        }

        return $subtotal;
    }

    /**
     * Returns order by user id and status.
     * @param int $user_id id of the user.
     * @param OrderStatus $status status of the order.
     * @return Order|null if any errors during execution, returns null. Otherwise, returns order.
     */
    public function getOrderByUserIdAndStatus(int $user_id, OrderStatus $status) : ?Order{
        return $this->order_rep->getOrderByUserIdAndStatus($user_id, $status);
    }

    /**
     * Check if order item is in cart, and if $order_id matches the id in $order_item. If both conditions are met, removes the order item.
     * @param int $order_id id of an order the order item is a part of.
     * @param int $item_id id of order item.
     * @param int $user_id id of the logged in user.
     * @throws EmptyCartException if no order with id can be found.
     * @throws Exception when the id of order in order item do not match provided $order_id.
     */
    public function removeOrderItemFromCart(int $order_id, int $item_id, int $user_id) : void {
        // Get user cart
        $order = $this->getOrderByUserIdAndStatus($user_id, OrderStatus::InCart);
        if($order == null) throw new EmptyCartException("Cart is empty.");
        if($order->order_id != $_POST['order_id']) throw new PostMismatchException("Order cart id and provided order id do not match.");

        // Get order item
        $item = $this->order_rep->getOrderItemById($item_id);
        if($item === null || $item === false) throw new EmptyPostException("No order item found with provided id.");
        if($item->order_id != $order_id) throw new PostMismatchException("");
        
        // Remove order item from the cart
        $remove = $this->order_rep->removeOrderItemFromCartOrder($order_id, $item_id);
        if(!$remove) throw new QueryExecutionException("Failed to remove order item.");

        // Remove booking
        $book_remove = $this->removeBookingById($item->booking_id, $item->booking_type);
        if(!$book_remove) throw new QueryExecutionException("Failed to remove booking.");

        Session::setCartItemsCount(Session::getCartItemsCount() - 1);
    }

    /**
     * Removes booking (uses different methods depending on booking type)
     * @param int $booking_id id of the booking
     * @param BookingType $booking_type type of the booking
     * @return bool returns true if operation was successfull, otherwise, returns false. 
     */
    private function removeBookingById(int $booking_id, BookingType $booking_type) : bool{
        switch($booking_type){
            case BookingType::Yummy:
                return $this->order_rep->removeYummyBooking($booking_id);
            case BookingType::History:
                return $this->order_rep->removeHistoryBooking($booking_id);   
            case BookingType::Stories:
                return $this->order_rep->removeStoryBooking($booking_id);   
            case BookingType::Jazz:
                return $this->order_rep->removeJazzBooking($booking_id);   
        }

        return false;
    }

    public function completeOrder(int $user_id) : void
    {
        // Get user
        $user = $this->user_rep->findById($user_id);
        if($user == null) throw new QueryExecutionException("Failed to get user with id.");

        // Get order 
        $order = $this->getOrderWithOrderItemsByUserId($user_id);
        if($order == null) throw new QueryExecutionException("Failed to get order with id.");

        // Update order status
        $subtotal_price = $this->calcOrderSubtotalPrice($order);
        $total_price = (int)(($subtotal_price * (self::$VAT_RATE + 10000)) / 10000);
        $order_date_time = new DateTime();

        $order_update = $this->order_rep->updateOrderToPaid($order->order_id, $order_date_time, $total_price);
        if(!$order_update) throw new QueryExecutionException("Failed to change order status.");

        $order->status = OrderStatus::Paid;
        $order->date = $order_date_time;
        $order->total_price = $total_price;
        
        // Generate tickets
        $tickets = [];

        foreach ($order->order_items as $item) {
            // Create Ticket object
            $ticket = new Ticket(); 
            $ticket->item_id = $item->item_id;
            $ticket->qr_token = bin2hex(random_bytes(24));
            $ticket->code = 'HF-' . strtoupper(bin2hex(random_bytes(12)));
            $ticket->order_item = $item;

            // Create ticket in db
            $t_id = $this->ticket_rep->createTicket($ticket);
            if($t_id == null) throw new QueryExecutionException("Failed to create ticket.");
            $ticket->ticket_id = $t_id;

            array_push($tickets, $ticket);
        }

        $this->sendTicketsAndInvoice($order, $tickets, $user);   
    }

    /**
     * Sends tickets and invoice by mail.
     * @param Order $order filled in order.
     * @param Ticket[] $tickets list of order tickets.
     * @param array $user asc array representing user.
     */
    private function sendTicketsAndInvoice(Order $order, array $tickets, array $user){
        $ticket_pdfs = [];

        // Get ticket pdfs
        foreach ($tickets as $ticket) {
            $qr_token = $ticket->qr_token;
            $quantity = $ticket->order_item->booking->getQuantityString();
            $event_name = $ticket->order_item->booking->getEventName();
            $address = $ticket->order_item->booking->getAddressFull();
            $date_string = $ticket->order_item->booking->getBookingStartDate()->format('d.m.Y H:i') . ' - ' . $ticket->order_item->booking->getBookingEndDate()->format('H:i');
            $ticket_code = $ticket->code;

            $pdf_ticket = $this->pdf_service->generateTicket($qr_token, $quantity, $event_name, $address, $date_string, $ticket_code);

            array_push($ticket_pdfs, $pdf_ticket);
        }

        // Get invoice pdf
        $invoice_pdf = $this->pdf_service->generateInvoice($order, $user);

        // Send emails
        $this->mail_service->sendOrderConfirmation($user['email'], $user['name'], $ticket_pdfs, $invoice_pdf, $tickets);
    }

    /*
    public function completeOrder(int $userId, array $cartItems, string $paymentMethod, array $user): int
    {
        // 1 — Create the order
        $orderId = $this->orderRepo->createOrder($userId, $paymentMethod);

        // 2 — Save order items + generate one ticket per quantity unit
        $generatedTickets = [];

        foreach ($cartItems as $item) {
            $typeId    = $this->resolveTypeId($item);
            $quantity  = (int)$item['quantity'];
            $unitPrice = (float)$item['price'];

            $this->orderRepo->addOrderItem($orderId, $typeId, $quantity, $unitPrice);

            for ($i = 0; $i < $quantity; $i++) {
                $qrToken    = bin2hex(random_bytes(24));
                $ticketCode = 'HF-' . strtoupper(bin2hex(random_bytes(3)));

                // Persist ticket for scanning
                $this->ticketRepo->createTicket($userId, $typeId, $qrToken, $ticketCode);

                // Collect data for PDF + email (cart items already carry event info)
                $generatedTickets[] = [
                    'event_name'       => $item['event_name']  ?? 'Festival Event',
                    'venue_name'       => $item['venue_name']  ?? '',
                    'start_time'       => $item['event_start'] ?? '',
                    'end_time'         => $item['event_end']   ?? '',
                    'ticket_type_name' => 'Regular Ticket',
                    'unit_price'       => $unitPrice,
                    'qr_token'         => $qrToken,
                    'ticket_code'      => $ticketCode,
                ];
            }
        }

        // 3 — Mark order as paid
        $this->orderRepo->markPaid($orderId);

        // 4 — Create invoice record
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $this->invoiceRepo->createInvoice($orderId, $total, 9.00, $user['name'], $user['email']);

        // 5 — Fetch invoice + order items for PDFs
        $invoice    = $this->invoiceRepo->getByOrder($orderId);
        $orderItems = $this->orderRepo->getOrderWithItems($orderId);

        // 6 — Generate one ticket PDF per ticket (with QR code)
        $ticketPdfs = [];
        foreach ($generatedTickets as $ticket) {
            $ticketPdfs[] = $this->pdfService->generateTicket($ticket, $user['name']);
        }

        // 7 — Generate invoice PDF
        $invoicePdf = $this->pdfService->generateInvoice($orderItems, $invoice);

        // 8 — Send single confirmation email with all PDFs + inline ticket summary
        $this->mailService->sendOrderConfirmation(
            $user['email'],
            $user['name'],
            $ticketPdfs,
            $invoicePdf,
            $invoice['invoice_number'],
            $generatedTickets
        );

        return $orderId;
    }

    // Resolves Ticket_Type.type_id from a cart item
    private function resolveTypeId(array $cartItem): int
    {
        $pdo  = $this->orderRepo->getConnection();
        $stmt = $pdo->prepare(
            "SELECT type_id FROM `Ticket_Type`
             WHERE event_id = :eid
             ORDER BY type_id ASC
             LIMIT 1"
        );
        $stmt->execute([':eid' => $cartItem['event_id']]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['type_id'] : 0;
    }
        */
}
