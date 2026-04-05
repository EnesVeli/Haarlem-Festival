<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<main class="book-main">
    <a class="book-back-link" href="<? echo '/yummy/restaurant?id=' . $view_model->restaurant->restaurant_id; ?>">← Back to Restaurant</a>
    <div class="book-card">
        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        <div>
            <div class="book-restaurant-title">Book a Table - <? echo htmlspecialchars($view_model->restaurant->name); ?></div>
            <div class="book-select-title">Select Number of People:</div>
            <div class="book-counter-section-container">
                <div class="book-counter-section">
                    <div class="book-counter-label">Adults:</div>
                    <div class="book-counter-container">
                        <button class="book-counter-button" onclick="adultCounterMinus()">
                            <div class="book-counter-button-minus">-</div>
                        </button>
                        <div class="book-counter-value" id="adult_counter">1</div>
                        <button class="book-counter-button" onclick="adultCounterPlus()">
                            <div class="book-counter-button-plus">+</div>
                        </button>
                    </div>
                </div>
                <div class="book-counter-section">
                    <div class="book-counter-label">Children:</div>
                    <div class="book-counter-container">
                        <button class="book-counter-button" onclick="childCounterMinus()">
                            <div class="book-counter-button-minus">-</div>
                        </button>
                        <div class="book-counter-value" id="child_counter">0</div>
                        <button class="book-counter-button" onclick="childCounterPlus()">
                            <div class="book-counter-button-plus">+</div>
                        </button>
                    </div>
                </div>

                <div class="book-cost-lebel" id="cost">€0</div>
            </div>
            <div>
                <div class="book-label">Select Date:</div>
                <select class="book-select-date" id="date" onchange="selectedDateChanged()">
                    <? for($i = 0; $i < 14; $i++): ?>
                        <option id="<? echo 'sel_date_' . $i; ?>" value="<? echo $i; ?>"><? echo htmlspecialchars($view_model->dates[$i])?></option>
                    <? endfor; ?>
                </select>
            </div>
            
                <? for($i = 0; $i < 14; $i++): ?>
                    <div id="<? echo 'slots_' . $i; ?>" style="display: none;">
                        <div class="book-label">Time Slots:</div>
                        <? if(count($view_model->time_slots[$i]) > 0): ?>         
                            <div class="book-time-slot-container">
                                <? for($j = 0; $j < count($view_model->time_slots[$i]); $j++): ?>
                                    <? $slot = $view_model->time_slots[$i][$j]; ?>
                                    <button class="<? echo 'book-time-slot ' . ($slot->capacity <= $slot->booked ? 'book-time-slot-not-active' : 'book-time-slot-not-selected'); ?>" sel="<? echo ($slot->capacity <= $slot->booked ? 0 : 1); ?>" value="<? echo $slot->slot_id; ?>" onclick="timeSlotButtonClick(this)">
                                        <div class="book-time-slot-time"><? echo $slot->time->format('H:i'); ?></div>
                                        <div class="book-time-slot-capacity"><? echo $slot->booked . '/' . $slot->capacity . ' slots'; ?></div>
                                    </button>
                                <? endfor; ?>
                            </div>
                        <? else: ?>
                            <div>No slots found for selected time.</div>
                        <? endif; ?>
                    </div>
                <? endfor; ?>
            
            <div>
                <div class="book-label">Additional Comments:</div>
                <textarea class="book-comment" id="comment" placeholder="*type your comments here*" maxlength="512"></textarea>
            </div>
            <div class="book-buttons-container">
                <a class="book-button book-button-cancel" href="<? echo '/yummy/restaurant?id=' . $view_model->restaurant->restaurant_id; ?>">Cancel</a>
                <button class="book-button book-button-submit-not-active" id="submit-button" onclick="onSubmitButtonClick()">Book</button>
            </div>
        </div>
    </div>
</main>

<script type="text/javascript">
    let restaurant_id = <? echo $view_model->restaurant->restaurant_id; ?>;

    let adult_count = 1;
    let child_count = 0;

    let date_offset = 0;

    let selected_button = null;

    selectedDateChanged();

    recalcCost();   

    function adultCounterPlus(){
        if(adult_count + 1 > 24) return;

        adult_count += 1;

        document.getElementById("adult_counter").innerHTML = adult_count;

        recalcCost();
    }

    function adultCounterMinus(){
        if(adult_count - 1 < 1) return;

        adult_count -= 1;

        document.getElementById("adult_counter").innerHTML = adult_count;

        recalcCost();
    }

    function childCounterPlus(){
        if(child_count + 1 > 24) return;

        child_count += 1;

        document.getElementById("child_counter").innerHTML = child_count;

        recalcCost();
    }

    function childCounterMinus(){
        if(child_count - 1 < 0) return;

        child_count -= 1;

        document.getElementById("child_counter").innerHTML = child_count;

        recalcCost();
    }

    function recalcCost(){
        document.getElementById("cost").innerHTML = '€' + ((adult_count + child_count) * 10);
    }

    function selectedDateChanged(){
        document.getElementById("slots_" + date_offset).style.display = 'none';

        date_offset = document.getElementById("date").selectedIndex;

        document.getElementById("slots_" + date_offset).style.display = 'inline';

        if(selected_button != null){
            selected_button.setAttribute("class", "book-time-slot book-time-slot-not-selected");
            selected_button.setAttribute("sel", 1);

            selected_button = null;

            updateSubmitButton();
        }
    }

    function timeSlotButtonClick(sender){
        if(sender.getAttribute("sel") == 1){
            if(selected_button != null){
                selected_button.setAttribute("class", "book-time-slot book-time-slot-not-selected");
                selected_button.setAttribute("sel", 1);
            }

            sender.setAttribute("class", "book-time-slot book-time-slot-selected");
            sender.setAttribute("sel", 2);

            selected_button = sender;
        }
        else if(sender.getAttribute("sel") == 2){
            sender.setAttribute("class", "book-time-slot book-time-slot-not-selected");
            sender.setAttribute("sel", 1);

            selected_button = null;
        }

        updateSubmitButton();
    }

    function updateSubmitButton(){
        if(selected_button != null){
            document.getElementById("submit-button").setAttribute("class", "book-button book-button-submit-active");
        }
        else{
            document.getElementById("submit-button").setAttribute("class", "book-button book-button-submit-not-active");
        }
    }

    function onSubmitButtonClick(){
        if(selected_button == null) return;
        
        post('/yummy/book', {'date_offset' : date_offset, 'adult_count' : adult_count, 'child_count' : child_count,
         'slot_id' : selected_button.getAttribute("value"), 'comment' : document.getElementById("comment").value, 'restaurant_id' : restaurant_id});
    }

    function post(path, params, method='post') {
        const form = document.createElement('form');
        form.method = method;
        form.action = path;

        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = params[key];

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

</script>

<?php require '/app/src/Views/partials/footer.php'; ?>