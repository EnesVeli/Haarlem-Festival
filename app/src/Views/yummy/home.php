<?php 
$guides = $guides ?? [];
$restaurants = $restaurants ?? [];

?>

<?php require '/app/src/Views/partials/header.php';?>

<link href="/assets/css/yummy.css" rel="stylesheet">

<section class="home-topper">
    <div>
        <div>The Festival / Events / Yummy</div>
        <h1>Food And Drinks</h1>
        <div>Discover Haarlem’s vibrant food and drink scene, from elegant fine dining restaurants and cosy cafes to lively bars and quick bite spots. Whether you’re looking for a relaxed coffee break, a casual lunch, craft cocktails, or an unforgettable dinner experience, Haarlem offers something for every taste, mood, and moment right in the heart of the city.</div>
    </div>

    <div class="home-topper-filter"></div>
    <img class="home-topper-img" src="/assets/css/uploads/yummy/home_topper.jpg">
</section>

<section>
    <h1>Our restaurant guides:</h1>

    <?php if(count($guides) > 0):?>
        <?php foreach($guides as $g):?>
            <div>
                <img src="<?'/assets/css/uploads/yummy/guides/' . $g['mini_img_path']?>">
                <h3><?$g['mini_title']?></h3>
                <div><?$g['mini_text']?></div>
                <a href="<?'/yummy/guide?id=' . $g['guide_id']?>">Learn more...</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div>No guides found.</div>
    <?php endif; ?>
</section>

<section>
    <h1>Places in Haarlem:</h1>

    <?php if(count($restaurants) > 0):?>
        <?php foreach($restaurants as $res):?>
            <div>
                <img src="<?'/assets/css/uploads/yummy/' . $g['mini_img_path']?>">
                <h3><?$g['mini_title']?></h3>
                <div>
                    <div><?round($g['rating'], 1, PHP_ROUND_HALF_UP)?></div>
                    <?php
                        $r = round($g['rating'] * 2, 0, PHP_ROUND_HALF_DOWN);

                        $star0 = $r >= 2 ? 2 : $r;
                        $star1 = $r - 2 >= 2 ? 2 : ($r - 2 <= 0 ? 0 : 1);
                        $star2 = $r - 4 >= 2 ? 2 : ($r - 4 <= 0 ? 0 : 1);
                        $star3 = $r - 6 >= 2 ? 2 : ($r - 6 <= 0 ? 0 : 1);
                        $star4 = $r - 8 >= 2 ? 2 : ($r - 8 <= 0 ? 0 : 1);

                        if($g['cost_rating'] == 3) $euro = '€€€';
                        else if($g['cost_rating'] == 2) $euro = '€€';
                        else $euro = '€';
                    ?>
                    <div>
                        <img src="<? '/assets/css/uploads/yummy/star/' . $star0 . '.png' ?>">
                        <img src="<? '/assets/css/uploads/yummy/star/' . $star1 . '.png' ?>">
                        <img src="<? '/assets/css/uploads/yummy/star/' . $star2 . '.png' ?>">
                        <img src="<? '/assets/css/uploads/yummy/star/' . $star3 . '.png' ?>">
                        <img src="<? '/assets/css/uploads/yummy/star/' . $star4 . '.png' ?>">
                    </div>
                    <div>.<?$euro?></div>
                    <a href="<?'/yummy/restaurant?id=' . $g['restaurant_id']?>">View...</a>
                </div>
                <div><?$g['mini_text']?></div>     
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div>No places found.</div>
    <?php endif; ?>

    <a href="/yumm/list">View all restaurants...</a>
</section>

<?php require '/app/src/Views/partials/footer.php'; ?>