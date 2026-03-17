<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<main class="book-main">
    <a href="<? echo '/yummy/restaurant?id=' . $view_model->restaurant->restaurant_id; ?>">← Back to Restaurant</a>
    <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    <div>
        <div>Book a Table - <? echo htmlspecialchars($view_model->restaurant->name); ?></div>
        <div>Select Number of People:</div>
        <div>
            <div>
                <div>Adults:</div>
                <div>
                    <button onclick="adultCounterMinus()">-</button>
                    <div id="adult_counter">1</div>
                    <button onclick="adultCounterPlus()">+</button>
                </div>
            </div>
            <div>
                <div>Children:</div>
                <div>
                    <button onclick="childCounterMinus()">-</button>
                    <div id="child_counter">0</div>
                    <button onclick="childCounterPlus()">+</button>
                </div>
            </div>

            <div id="cost">€0</div>
        </div>
        <div>
            <div>Select Date</div>
        </div>
        <div>
            <? if(count($view_model->time_slots) > 0): ?>
                <div>Select Time Slot:</div>
                <div>
                    <? foreach($view_model->time_slots as $slot): ?>
                        <button>
                            <div><? echo $slot->time->format('H:i'); ?></div>
                            <div><? echo $slot->booked . '/' . $slot->capacity; ?></div>
                        </button>
                    <? endforeach; ?>
                </div>
            <? else: ?>
                <div>No slots found for selected time.</div>
            <? endif; ?>
        </div>
        <div>
            <div>Additional Comments:</div>
        </div>
        <div>
            <a href="<? echo '/yummy/restaurant?id=' . $view_model->restaurant->restaurant_id; ?>">Cancel</a>
            <button>Book</button>
        </div>
    </div>
</main>

<script type="text/javascript">
    let adult_count = 1;
    let child_count = 0;

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
</script>

<?php require '/app/src/Views/partials/footer.php'; ?>