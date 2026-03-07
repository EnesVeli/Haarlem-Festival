<?php 
$restaurants = $restaurants ?? [];

$pl_types = $pl_types ?? [];
$ml_types = $ml_types ?? [];
$fd_types = $fd_types ?? [];
$cs_types = $cs_types ?? [];
?>

<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<main class="list-main">
    <section>
        <button href="/yummy">&lt;- Back to Yummy Home Page</button>

        <div>
            <h1>Restaurants, Cafes and Bars</h1>
            <div>Haarlem has built button strong reputation as button destination for high-quality dining, perfectly reflect the city’s diverse and refined food scene. Each offers button distinct experience, catering to different moods while maintaining button consistently high standard.</div>
        </div>

        <div></div>
        <img src="/assets/css/uploads/yummy/list_topper.jpg">
    </section> 

    <section>
        <h1>Filter:</h1>
        <div>
            <div>
                <div>Place Type:</div>
                <? if(count($pl_types) > 0): ?>
                    <? foreach($pl_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

            <div>
                <div>Meal Type:</div>
                <? if(count($ml_types) > 0): ?>
                    <? foreach($ml_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            
            <div>
                <div>Food Type:</div>
                <? if(count($fd_types) > 0): ?>
                    <? foreach($fd_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>        

            <div>
                <div>Cuisine:</div>
                <? if(count($cs_types) > 0): ?>
                    <? foreach($cs_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            
            <button onclick="filterListButtonClick()">Filter</button>
        </div>
    </section>

    <section>
        <div>
            <div>x palces found</div>
            <div>
                <div>Sorted By</div>
                <select>
                    <option>Popularity</option>
                </select>
            </div>
        </div>
        <div></div>
        <div class="list-restaurant-list">
            <? if(count($restaurants) > 0): ?>
                <?php foreach($restaurants as $res):?>
                        <div class="home-restaurant-card">
                            <img class="home-restaurant-img" src="<? echo '/assets/css/uploads/yummy/restaurants/' . $res['mini_img_path']; ?>">
                            <h3 class="home-restaurant-title"><? echo $res['name'];?></h3>
                            <div class="home-restaurant-card-sub">
                                <div class="home-restaurant-rating"><? echo number_format((float)round($res['rating'], 1, PHP_ROUND_HALF_UP), 1, '.', '') ?></div>
                                <?php
                                    $r = round($res['rating'] * 2, 0, PHP_ROUND_HALF_DOWN);

                                    $star0 = $r >= 2 ? 2 : $r;
                                    $star1 = $r - 2 >= 2 ? 2 : ($r - 2 <= 0 ? 0 : 1);
                                    $star2 = $r - 4 >= 2 ? 2 : ($r - 4 <= 0 ? 0 : 1);
                                    $star3 = $r - 6 >= 2 ? 2 : ($r - 6 <= 0 ? 0 : 1);
                                    $star4 = $r - 8 >= 2 ? 2 : ($r - 8 <= 0 ? 0 : 1);

                                    if($res['cost_rating'] == 3) $euro = '€€€';
                                    else if($res['cost_rating'] == 2) $euro = '€€';
                                    else $euro = '€';
                                ?>
                                <div class="home-restaurant-star-container">
                                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $star0 . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $star1 . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $star2 . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $star3 . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $star4 . '.png'; ?>">
                                </div>
                                <div class="home-restaurant-euro-dot">.</div>
                                <div class="home-restaurant-euro"><? echo $euro; ?></div>
                                <a class="home-restaurant-view_button" href="<? echo '/yummy/restaurant?id=' . $res['restaurant_id']; ?>">View...</a>
                            </div>
                            <div class="home-restaurant-text"><? echo $res['mini_text']; ?></div>     
                        </div>
                    <?php endforeach; ?>
            <? endif; ?>
        </div>
        <div></div>
        <div>

        </div>
    </section>
</main>

<script type="text/javascript">
    let place_type = <?php echo json_encode($place_type); ?>;
    let meal_type = <?php echo json_encode($meal_type); ?>;
    let food_type = <?php echo json_encode($food_type); ?>;
    let cuisine_type = <?php echo json_encode($cuisine_type); ?>;

    for(let i = 0; i < place_type.length; i++){
        document.getElementById(place_type[i]).className = "list-filter list-filter-selected";
    }

    for(let i = 0; i < meal_type.length; i++){
        document.getElementById(meal_type[i]).className = "list-filter list-filter-selected";
    }
   
    for(let i = 0; i < food_type.length; i++){
        document.getElementById(food_type[i]).className = "list-filter list-filter-selected";
    }   

    for(let i = 0; i < cuisine_type.length; i++){
        document.getElementById(cuisine_type[i]).className = "list-filter list-filter-selected";
    }

    function filterClick(sender){
        if(sender.getAttribute('class') == "list-filter list-filter-selected"){ // Unsel a filter
            sender.className = "list-filter list-filter-not-selected";
            
            switch(sender.getAttribute('category')){
                case '0':
                    removeItem(place_type, sender.getAttribute('id'));
                    break;
                case '1':
                    removeItem(meal_type, sender.getAttribute('id'));
                    break;
                case '2':
                    removeItem(food_type, sender.getAttribute('id'));
                    break;
                case '3':
                    removeItem(cuisine_type, sender.getAttribute('id'));
                    break;
            }
        }
        else{ // Sel a filter
            sender.className = "list-filter list-filter-selected";

            switch(sender.getAttribute('category')){
                case '0':
                    place_type.push(sender.getAttribute('id'));
                    break;
                case '1':
                    meal_type.push(sender.getAttribute('id'));
                    break;
                case '2':
                    food_type.push(sender.getAttribute('id'));
                    break;
                case '3':
                    cuisine_type.push(sender.getAttribute('id'));
                    break;
            }           
        } 
    } 

    function filterListButtonClick(){
        let places = getListArguments(place_type, 'place_type');
        let meals = getListArguments(meal_type, 'meal_type');
        let foods = getListArguments(food_type, 'food_type');
        let cuisines = getListArguments(cuisine_type, 'cuisine_type');
        
        let uri = '/yummy/list?'

        if(places != null){
            uri += places + '&'
        } 
        
        if(meals != null){
            uri += meals + '&'
        }

        if(foods != null){
            uri += foods + '&'
        }

        if(cuisines != null){
            uri += cuisines + '&'
        }

        uri += 'sorting=' + 'def';

        window.location.href = uri;
    }

    function removeItem(array, item){
        let index = array.indexOf(item);

        if (index > -1) { // If item found remove it
            array.splice(index, 1);
        }
    }

    function getListArguments(array, name){
        if(array.length == 0) return null;
        else if (array.length == 1) return name + '=' + array[0];
        else{
            let arg = name + '=' + array[0];

            for(let i = 1; i < array.length; i++){
                arg += ',' + array[i];
            }

            return arg;
        }
    }
</script>

<?php require '/app/src/Views/partials/footer.php'; ?>