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
    <section class="list-topper">
        <a class="list-topper-back" href="/yummy">← Back to Yummy Home Page</a>
        <div class="list-topper-container">
            <div class="list-topper-text-container">
                <h1 class="list-topper-title">Restaurants, Cafes and Bars</h1>
                <div class="list-topper-text">Haarlem has built button strong reputation as button destination for high-quality dining, perfectly reflect the city’s diverse and refined food scene. Each offers button distinct experience, catering to different moods while maintaining button consistently high standard.</div>
            </div>

            <div class="list-topper-filter"></div>
            <img class="list-topper-img" src="/assets/css/uploads/yummy/list_topper.jpg">
        </div>     
    </section> 

    <section class="list-filter-section">
        <h1 class="list-filter-title">Filter:</h1>
        <div class="list-filter-container">
            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Place Type:</div>
                <? if(count($pl_types) > 0): ?>
                    <? foreach($pl_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Meal Type:</div>
                <? if(count($ml_types) > 0): ?>
                    <? foreach($ml_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            
            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Food Type:</div>
                <? if(count($fd_types) > 0): ?>
                    <? foreach($fd_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>        

            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Cuisine:</div>
                <? if(count($cs_types) > 0): ?>
                    <? foreach($cs_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t['name']); ?>" category="<? echo htmlspecialchars($t['category']); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t['name']) ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>        
        </div>
        
        <div class="list-sorting-title">Sorting:</div>
                <select id="sort" class="list-sort">
                    <option id="sort-opt-0" value="0" index="0">Name Ascending</option>
                    <option id="sort-opt-1" value="1" index="1">Name Descending</option>
                    <option id="sort-opt-2" value="2" index="2">Rating Ascending</option>
                    <option id="sort-opt-3" value="3" index="3">Rating Descending</option>
                    <option id="sort-opt-4" value="4" index="4">Cost Rating Ascending</option>
                    <option id="sort-opt-5" value="5" index="5">Cost Rating Descending</option>
                </select>

        <button class="list-filter-all-button" onclick="reloadFilterSortPage()">Filter</button>
    </section>

    <section class="list-restaurants-section">
        <div class="list-place-found-label"><? echo $count_resturants; ?> palces found</div>
        <div class="list-line"></div>
        <div class="list-restaurant-list">
            <? if(count($restaurants) > 0): ?>
                <?php foreach($restaurants as $res):?>
                    <div class="list-restaurant-card">
                        <img class="home-restaurant-img" src="<? echo '/assets/css/uploads/yummy/restaurants/' . $res['mini_img_path']; ?>">
                        <h3 class="home-restaurant-title"><? echo htmlspecialchars($res['name']);?></h3>
                        <div class="home-restaurant-card-sub">
                            <div class="home-restaurant-rating"><? echo number_format((float)round($res['rating'], 1, PHP_ROUND_HALF_UP), 1, '.', '') ?></div>
                            <?php
                                // Calculate number of stars 
                                $r = round($res['rating'] * 2, 0, PHP_ROUND_HALF_DOWN);

                                $star0 = $r >= 2 ? 2 : $r;
                                $star1 = $r - 2 >= 2 ? 2 : ($r - 2 <= 0 ? 0 : 1);
                                $star2 = $r - 4 >= 2 ? 2 : ($r - 4 <= 0 ? 0 : 1);
                                $star3 = $r - 6 >= 2 ? 2 : ($r - 6 <= 0 ? 0 : 1);
                                $star4 = $r - 8 >= 2 ? 2 : ($r - 8 <= 0 ? 0 : 1);
                                
                                // Calculate cost rating
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
                        <div class="home-restaurant-text"><? echo htmlspecialchars($res['mini_text']); ?></div>     
                    </div>
                <?php endforeach; ?>
            <? endif; ?>
        </div>
        <? if($count_resturants > $count_res_per_page):?>
            <div class="list-line"></div>
            <div class="list-pages-container">
                <? if($page != 0):?>
                    <button class="list-page-next-prev" onclick="reloadFilterSortPage(0)">&lt;&lt;</button>
                    <button class="list-page-next-prev" onclick="previousPageClick()">&lt;</button>
                <? endif; ?>

                <? 
                    $page_count = round($count_resturants / $count_res_per_page, 0, RoundingMode::AwayFromZero); // Number of pages staring from one

                    $offset = 0; // Left offset of pages button
                    $limit = 0; // Right offset of pages button

                    if($page < abs($page - $page_count + 1)){ // If current page is closer to first page than last, start from offset
                        for (; $offset < 3; $offset++) { 
                            if($page - $offset <= 0) break;
                        }

                        for (; $limit < 7 - $offset; $limit++) { 
                            if($page + $limit >= $page_count) break;
                        }

                    }  
                    else{ // Otherwise from limit
                        for (; $limit < 4; $limit++) { 
                            if($page + $limit >= $page_count) break;
                        }

                        for (; $offset < 7 - $limit; $offset++) { 
                            if($page - $offset <= 0) break;
                        }                       
                    }                  
                ?>

                <? for($i = $page - $offset; $i < $page + $limit; $i++):?>
                    <button id="page-<? echo $i?>" class="list-page-button list-page-button-unsel" onclick="reloadFilterSortPage(<? echo $i;?>)"><? echo $i + 1; ?></button>
                <? endfor; ?>
                
                <? if($page != $page_count - 1):?>
                    <button class="list-page-next-prev" onclick="nextPageClick()">&gt;</button>
                    <button class="list-page-next-prev" onclick="reloadFilterSortPage(<? echo $page_count - 1;?>)">&gt;&gt;</button>
                <? endif; ?>
            </div>
        <? endif; ?>
    </section>
</main>

<script type="text/javascript">
    // Get current page
    let current_page = <?php echo $page?>;
    let last_page = <?php echo round($count_resturants / $count_res_per_page, 0, RoundingMode::AwayFromZero); ?>

    // Get all of the selected filter types by category
    let place_type = <?php echo json_encode($place_type); ?>;
    let meal_type = <?php echo json_encode($meal_type); ?>;
    let food_type = <?php echo json_encode($food_type); ?>;
    let cuisine_type = <?php echo json_encode($cuisine_type); ?>;

    // Select all of the filters from uri
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

    // Select page button
    if(last_page > 1) document.getElementById("page-" + current_page).className = "list-page-button list-page-button-sel";

    // Select sorting from uri
    document.getElementById("sort").selectedIndex = <? echo $sorting; ?>;

    // Filter option button click action
    function filterOptionClick(sender){
        if(sender.getAttribute('class') == "list-filter list-filter-selected"){ // Filter is selected
            sender.className = "list-filter list-filter-not-selected"; // Unsel it (css)
            
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
        else{ // Filter is not selected
            sender.className = "list-filter list-filter-selected"; // Selected it (css)

            switch(sender.getAttribute('category')){ // Add filter to corresponding array of filters
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

    // Filter button click action
    function reloadFilterSortPage(page = 0){
        // Get strings of all of the selected filters as parameters for the uri
        let places = getListArguments(place_type, 'place_type');
        let meals = getListArguments(meal_type, 'meal_type');
        let foods = getListArguments(food_type, 'food_type');
        let cuisines = getListArguments(cuisine_type, 'cuisine_type');
        
        let uri = '/yummy/list?' // Set base for uri

        // Add filters to uri as needed
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

        uri += 'sorting=' + document.getElementById("sort").value; // Add sorting

        uri += '&page=' + page; // Add page number

        current_page = page; // Reset currant page number

        window.location.href = uri; // Go to crafted uri
    }

    function previousPageClick(){
        if(current_page > 0){
            reloadFilterSortPage(current_page - 1);
        }
    }

    function nextPageClick(){
        if(current_page < last_page - 1){
            reloadFilterSortPage(current_page + 1);
        }
    }

    // Removes item from the list
    function removeItem(array, item){
        let index = array.indexOf(item);

        if (index > -1) { // If item found remove it
            array.splice(index, 1);
        }
    }

    // Returns list of selected filter as parameters for uri
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