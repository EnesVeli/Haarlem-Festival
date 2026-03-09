<?php 
$guides = $guides ?? [];
$restaurants = $restaurants ?? [];
?>

<?php require '/app/src/Views/partials/header.php';?>


<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>
<!-- <link href="/assets/css/yummy.css" rel="stylesheet"> -->

<main class="home-main">
    <section class="home-topper">
        <div class="home-topper-text-container">
            <div class="home-topper-little-text">The Festival / Events / Yummy</div>
            <h1 class="home-topper-title">Food And Drinks</h1>
            <div class="home-topper-text">Discover Haarlem’s vibrant food and drink scene, from elegant fine dining restaurants and cosy cafes to lively bars and quick bite spots. Whether you’re looking for a relaxed coffee break, a casual lunch, craft cocktails, or an unforgettable dinner experience, Haarlem offers something for every taste, mood, and moment right in the heart of the city.</div>
        </div>

        <div class="home-topper-filter"></div>
        <img class="home-topper-img" src="/assets/css/uploads/yummy/home_topper.jpg">
    </section>

    <section class="home-restaurant-section">
        <div class="home-restaurants-title-container">
            <h1 class="home-restaurants-title">Places in Haarlem:</h1>
            <a class="home-restaurants-view-all-top" href="/yummy/list">view all</a>
        </div>      
   
        <?php if(count($restaurants) > 0):?>
            <div class="home-restaurant-list">
                <div class="home-restaurant-list-wrap">
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
                </div>
            </div>         
        <?php else: ?>
            <div class="home-not-found-label">No places found.</div>
        <?php endif; ?>
        
        <div class="home-restaurants-view-all-container">
            <a class="home-restaurants-view-all-button" href="/yummy/list">View all restaurants...</a>
        </div>        
    </section>

    <section class="home-guide-section">
        <h1 class="home-guides-title">Our restaurant guides:</h1>            
        <?php if(count($guides) > 0):?>
            <div class="home-guide-list">
                <div class="home-guide-list-wrap">
                    <?php foreach($guides as $g):?>
                        <div class="home-guide-card">
                            <img class="home-guide-img" src="<? echo '/assets/css/uploads/yummy/guides/' . $g['mini_img_path']; ?>">
                            <h3 class="home-guide-title"><? echo $g['mini_title']; ?></h3>
                            <div class="home-guide-text"><? echo $g['mini_text'];?></div>
                            <a class="home-guide-view-button" href="<? echo '/yummy/guide?id=' . $g['guide_id']; ?>">Learn more...</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>   
        <?php else: ?>
            <div class="home-not-found-label">No guides found.</div>
        <?php endif; ?>                   
    </section>
</main>

<?php require '/app/src/Views/partials/footer.php'; ?>