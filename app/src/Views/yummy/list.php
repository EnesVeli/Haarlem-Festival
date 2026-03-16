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

    <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <section class="list-filter-section">
        <h1 class="list-filter-title">Filter:</h1>
        <div class="list-filter-container">
            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Place Type:</div>
                <? if(count($view_model->all_place_types) > 0): ?>
                    <? foreach($view_model->all_place_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t->name); ?>" category="<? echo htmlspecialchars($t->category); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t->name); ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Meal Type:</div>
                <? if(count($view_model->all_meal_types) > 0): ?>
                    <? foreach($view_model->all_meal_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t->name); ?>" category="<? echo htmlspecialchars($t->category); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t->name); ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            
            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Food Type:</div>
                <? if(count($view_model->all_food_types) > 0): ?>
                    <? foreach($view_model->all_food_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t->name); ?>" category="<? echo htmlspecialchars($t->category); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t->name); ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>        

            <div class="list-filter-sub">
                <div class="list-filter-sub-title">Cuisine:</div>
                <? if(count($view_model->all_cuisine_types) > 0): ?>
                    <? foreach($view_model->all_cuisine_types as $t): ?>
                        <button id="<? echo htmlspecialchars($t->name); ?>" category="<? echo htmlspecialchars($t->category); ?>" onclick="filterOptionClick(this)" class="list-filter list-filter-not-selected"><? echo htmlspecialchars($t->name); ?></button>
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
        <div class="list-place-found-label"><? echo $view_model->total_found_restaurants_number; ?> palces found</div>
        <div class="list-line"></div>
        <div class="list-restaurant-list">
            <? if(count($view_model->restaurants) > 0): ?>
                <?php foreach($view_model->restaurants as $res):?>
                    <div class="list-restaurant-card">
                        <img class="home-restaurant-img" src="<? echo '/assets/css/uploads/yummy/restaurants/' . $res->main_img_path; ?>">
                        <h3 class="home-restaurant-title"><? echo htmlspecialchars($res->name); ?></h3>
                        <div class="home-restaurant-card-sub">
                            <div class="home-restaurant-rating"><? echo $res->getRatingFormated(); ?></div>
                            <? $stars = $res->getStars(); ?>
                            <div class="home-restaurant-star-container">
                                <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[0] . '.png'; ?>">
                                <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[1] . '.png'; ?>">
                                <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[2] . '.png'; ?>">
                                <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[3] . '.png'; ?>">
                                <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[4] . '.png'; ?>">
                            </div>
                            <div class="home-restaurant-euro-dot">.</div>
                            <div class="home-restaurant-euro"><? echo $res->getCostRatingString(); ?></div>
                            <a class="home-restaurant-view_button" href="<? echo '/yummy/restaurant?id=' . $res->restaurant_id; ?>">View...</a>
                        </div>
                        <div class="home-restaurant-text"><? echo htmlspecialchars($res->mini_text); ?></div>     
                    </div>
                <?php endforeach; ?>
            <? endif; ?>
        </div>
        <? if($view_model->total_pages_number > 1):?>
            <div class="list-line"></div>
            <div class="list-pages-container">
                <? if($view_model->current_page != 0):?>
                    <button class="list-page-next-prev" onclick="reloadFilterSortPage(0)">&lt;&lt;</button>
                    <button class="list-page-next-prev" onclick="previousPageClick()">&lt;</button>
                <? endif; ?>

                <? for($i = $view_model->current_page - $view_model->page_offset; $i < $view_model->current_page + $view_model->page_limit; $i++):?>
                    <button id="page-<? echo $i?>" class="list-page-button list-page-button-unsel" onclick="reloadFilterSortPage(<? echo $i;?>)"><? echo $i + 1; ?></button>
                <? endfor; ?>
                
                <? if($view_model->current_page != $view_model->total_pages_number - 1):?>
                    <button class="list-page-next-prev" onclick="nextPageClick()">&gt;</button>
                    <button class="list-page-next-prev" onclick="reloadFilterSortPage(<? echo $view_model->total_pages_number - 1;?>)">&gt;&gt;</button>
                <? endif; ?>
            </div>
        <? endif; ?>
    </section>
</main>

<script type="text/javascript">
    // Get current page
    let current_page = <?php echo $view_model->current_page?>;
    let last_page = <?php echo $view_model->total_pages_number; ?>

    // Get all of the selected filter types by category
    let place_type = <?php echo json_encode($view_model->current_place_types); ?>;
    let meal_type = <?php echo json_encode($view_model->current_meal_types); ?>;
    let food_type = <?php echo json_encode($view_model->current_food_types); ?>;
    let cuisine_type = <?php echo json_encode($view_model->current_cuisine_types); ?>;

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
    document.getElementById("sort").selectedIndex = <? echo $view_model->sorting; ?>;

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