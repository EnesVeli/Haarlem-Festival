<?php require '/app/src/Views/partials/header.php';?>

<style>
    <?php include '/app/public/assets/css/yummy.css'; ?>
</style>

<main class="restaurant-main">
    <div class="restaurant-main-container">     
        <div class="restaurant-main-left">
            <a class="restaurant-go-back-link" href="/yummy/list">← Back to Restaurants List</a>
            <h1><? echo htmlspecialchars($view_model->restaurant->name)?></h1>
            <img src="<? echo '/assets/css/uploads/yummy/restaurants/' . $view_model->restaurant->main_img_path; ?>">
            <? if(count($view_model->images) > 0): ?>
                <div>
                    <? foreach($view_model->images as $image): ?>
                        <img src="<? echo '/assets/css/uploads/yummy/restaurants/' . $image->path; ?>">
                    <? endforeach; ?>
                </div>
            <? endif; ?>
            <div>

            </div>
            <div><? echo htmlspecialchars($view_model->restaurant->text)?></div>
            <div>
                <h3>Example of Dishes</h3>

            </div>
        </div>
        <div class="restaurant-main-right">
            <div>
                <div>Book a Table:</div>
                <a>Book</a>
            </div>
            <div>
                <div>Score</div>
                <div class="home-restaurant-rating"><? echo $view_model->restaurant->getRatingFormated(); ?></div>
                <? $stars = $view_model->restaurant->getStars(); ?>
                <div class="home-restaurant-star-container">
                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[0] . '.png'; ?>">
                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[1] . '.png'; ?>">
                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[2] . '.png'; ?>">
                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[3] . '.png'; ?>">
                    <img class="home-restaurant-star" src="<? echo '/assets/css/uploads/yummy/star/' . $stars[4] . '.png'; ?>">
                </div>
                <div class="home-restaurant-euro-dot">.</div>
                <div class="home-restaurant-euro"><? echo $view_model->restaurant->getCostRatingString(); ?></div>
            </div>
            <div>
                <div>Opening Hours*:</div>
                <div class="restaurant-opening-hours-weekdays">Monday&#10;Tuesday&#10;Wednesday&#10;Thursday&#10;Friday&#10;Saturday&#10;Sunday</div>
                <div class="restaurant-opening-hours-weekdays"><? echo htmlspecialchars($view_model->restaurant->opening_hours); ?></div>
                <div>*(Opening hours may change on holidays)</div>
            </div>
            <div>
                <div>Location:</div>
                <a><? echo htmlspecialchars($view_model->restaurant->address_text); ?></a>
                <div>
                    <iframe class="map-iframe" src="<? echo 'https://www.google.com/maps?q=' . $view_model->restaurant->address_uri . '&output=embed'; ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>
            <div>
                <div>Tags</div>
                <? foreach($view_model->tags as $tag):?>
                    <div><? echo htmlspecialchars($tag->name); ?></div>
                <? endforeach; ?>
            </div>
            <div>
                <? if($view_model->restaurant->website_link != null): ?>
                    <div>Website:</div>
                    <a href="<? echo htmlspecialchars($view_model->restaurant->website_link); ?>">Link...</a>
                <? endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require '/app/src/Views/partials/footer.php'; ?>