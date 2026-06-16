<?php
/** @var  \App\ViewModels\Yummy\YummyRestaurantViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'Yummy - Restaurant - ' . $view_model->restaurant->name;
$pageCSS = 'yummy.css'; 
?>

<?php require '/app/src/Views/partials/header.php';?>

<main class="restaurant-main">
    <div class="restaurant-main-container">     
        <div class="restaurant-main-left">
            <a class="restaurant-go-back-link" href="/yummy/list">← Back to Restaurants List</a>
            <?php if(!empty($error_message)): ?>
                <div class="main-error" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            <h1 class="restaurant-name-title"><? echo htmlspecialchars($view_model->restaurant->name); ?></h1>
            <div class="restaurant-main-image-container">
                <img class="restaurant-main-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->restaurant->main_img_path; ?>">
            </div>
            <? if(count($view_model->images) > 0): ?>
                <? if(count($view_model->images) > 5): ?>
                    <div class="restaurant-images-container">
                        <? for($i = 0; $i < 5; $i++): ?>
                            <div class="restaurant-sub-image-container">
                                <img class="restaurant-sub-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->images[$i]->path; ?>">
                            </div>                      
                        <? endfor; ?>
                    </div>
                    <div class="restaurant-images-container">
                        <? for($i = 5; $i < count($view_model->images); $i++): ?>
                            <div class="restaurant-sub-image-container">
                                <img class="restaurant-sub-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $view_model->images[$i]->path; ?>">
                            </div>                      
                        <? endfor; ?>
                    </div>
                <? else: ?>
                    <div class="restaurant-images-container">
                        <? foreach($view_model->images as $image): ?>
                            <div class="restaurant-sub-image-container">
                                <img class="restaurant-sub-image" src="<? echo '/assets/uploads/yummy/restaurants/' . $image->path; ?>">
                            </div>                      
                        <? endforeach; ?>
                    </div>
                <? endif; ?>
            <? endif; ?>
            <div class="restaurant-main-text"><? echo htmlspecialchars($view_model->restaurant->text)?></div>
            <? if(count($view_model->dishes) > 0): ?>
                <div class="restaurant-dishes-main">
                    <h3 class="restaurant-dishes-title">Example of Dishes</h3>
                    <div class="restaurant-dishes-container">
                        <? foreach($view_model->dishes as $dish): ?>
                            <div class="restaurant-dish">
                                <div class="restaurant-dish-image-container">
                                    <img class="restaurant-dish-image" src="<? echo '/assets/uploads/yummy/restaurants/dishes/' . $dish->image_path; ?>">
                                </div>      
                                <div class="restaurant-dish-name"><? echo htmlspecialchars($dish->name); ?></div>
                                <div class="restaurant-dish-text"><? echo htmlspecialchars($dish->text); ?></div>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            <? endif; ?>
        </div>
        <div class="restaurant-main-right">
            <div>
                <div>Book a Table:</div>
                <a class="restaurant-book-button" href="<? echo '/yummy/book?id=' . $view_model->restaurant->restaurant_id?>">Book</a>
            </div>
            <div>
                <div>Score:</div>
                <div class="restaurant-score-container">
                    <div class="home-restaurant-rating"><? echo $view_model->restaurant->getRatingFormated(); ?></div>
                    <? $stars = $view_model->restaurant->getStars(); ?>
                    <div class="home-restaurant-star-container">
                        <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[0] . '.png'; ?>">
                        <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[1] . '.png'; ?>">
                        <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[2] . '.png'; ?>">
                        <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[3] . '.png'; ?>">
                        <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[4] . '.png'; ?>">
                    </div>
                    <div class="home-restaurant-euro-dot">.</div>
                    <div class="home-restaurant-euro"><? echo $view_model->restaurant->getCostRatingString(); ?></div>
                </div>
            </div>
            <div>
                <div>Opening Hours*:</div>
                <div class="restaurant-opening-hours-container">
                    <div class="restaurant-opening-hours-weekdays">Monday&#10;Tuesday&#10;Wednesday&#10;Thursday&#10;Friday&#10;Saturday&#10;Sunday</div>
                    <div class="restaurant-opening-hours-time"><? echo htmlspecialchars($view_model->hours->monday); ?>&#10;<? echo htmlspecialchars($view_model->hours->tuesday); ?>&#10;<? echo htmlspecialchars($view_model->hours->wednesday); ?>&#10;<? echo htmlspecialchars($view_model->hours->thursday); ?>&#10;<? echo htmlspecialchars($view_model->hours->friday); ?>&#10;<? echo htmlspecialchars($view_model->hours->saturday); ?>&#10;<? echo htmlspecialchars($view_model->hours->sunday); ?></div>
                </div>
                <div class="restaurant-opening-hours-comment">*(Opening hours may change on holidays)</div>
            </div>
            <div>
                <div>Location:</div>
                <a class="restaurant-location-address" href="<? echo 'https://www.google.com/maps?q=' . $view_model->restaurant->address_uri; ?>"><? echo htmlspecialchars($view_model->restaurant->address_text); ?></a>
                <div class="restaurant-map-container">
                    <iframe class="restaurant-map" src="<? echo 'https://www.google.com/maps?q=' . $view_model->restaurant->address_uri . '&output=embed'; ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>
            <div>
                <div>Tags</div>
                <? foreach($view_model->tags as $tag):?>
                    <div class="restaurant-tag">
                        <div class="restaurant-tag-dot">.&nbsp;</div>
                        <div class="restaurant-tag-text"><? echo htmlspecialchars($tag->name); ?></div>
                    </div>              
                <? endforeach; ?>
            </div>
            <div>
                <? if($view_model->restaurant->website_link != null): ?>
                    <div>Website:</div>
                    <a class="restaurant-website-link" href="<? echo htmlspecialchars($view_model->restaurant->website_link); ?>">Link...</a>
                <? endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require '/app/src/Views/partials/footer.php'; ?>