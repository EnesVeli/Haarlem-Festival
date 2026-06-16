<?php
/** @var \App\ViewModels\Yummy\YummyHomeViewModel $view_model */
/** @var ?string $error_message */

$pageTitle = 'Yummy - Haarlem Festival';
$pageCSS = 'yummy.css';
?>

<?php require '/app/src/Views/partials/header.php'; ?>

<main class="home-main">
    <section class="home-topper">
        <div class="home-topper-text-container">
            <div class="home-topper-little-text">The Festival / Events / Yummy</div>
            <h1 class="home-topper-title"><? echo htmlspecialchars($view_model->title); ?></h1>
            <div class="home-topper-text"><? echo htmlspecialchars($view_model->subtitle); ?></div>
        </div>

        <div class="home-topper-filter"></div>
        <img class="home-topper-img" src="<? echo '/assets/uploads/yummy/topper/' . $view_model->topper_path; ?>">
    </section>

    <section class="home-restaurant-section">
        <div class="home-restaurants-title-container">
            <h1 class="home-restaurants-title">Places in Haarlem:</h1>
            <a class="home-restaurants-view-all-top" href="/yummy/list">view all</a>        
        </div>  
        
        <?php if(!empty($error_message)): ?>
            <div class="main-error" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>    
   
        <?php if(count($view_model->restaurants) > 0):?>
            <div class="home-restaurant-list">
                <div class="home-restaurant-list-wrap">
                    <?php foreach($view_model->restaurants as $res):?>
                        <div class="home-restaurant-card">
                            <img class="home-restaurant-img" src="<? echo '/assets/uploads/yummy/restaurants/' . $res->main_img_path; ?>">
                            <h3 class="home-restaurant-title"><? echo htmlspecialchars($res->name); ?></h3>
                            <div class="home-restaurant-card-sub">
                                <div class="home-restaurant-rating"><? echo $res->getRatingFormated(); ?></div>
                                <? $stars = $res->getStars(); ?>
                                <div class="home-restaurant-star-container">
                                    <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[0] . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[1] . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[2] . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[3] . '.png'; ?>">
                                    <img class="home-restaurant-star" src="<? echo '/assets/uploads/yummy/star/' . $stars[4] . '.png'; ?>">
                                </div>
                                <div class="home-restaurant-euro-dot">.</div>
                                <div class="home-restaurant-euro"><? echo $res->getCostRatingString(); ?></div>
                                <a class="home-restaurant-view_button" href="<? echo '/yummy/restaurant?id=' . $res->restaurant_id; ?>">View...</a>
                            </div>
                            <div class="home-restaurant-text"><? echo htmlspecialchars($res->mini_text); ?></div>     
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
        <?php if(count($view_model->guides) > 0):?>
            <div class="home-guide-list">
                <div class="home-guide-list-wrap">
                    <?php foreach($view_model->guides as $g):?>
                        <div class="home-guide-card">
                            <img class="home-guide-img" src="<? echo '/assets/uploads/yummy/guides/' . $g->mini_img_path; ?>">
                            <h3 class="home-guide-title"><? echo htmlspecialchars($g->mini_title); ?></h3>
                            <div class="home-guide-text"><? echo htmlspecialchars($g->mini_text); ?></div>
                            <a class="home-guide-view-button" href="<? echo '/yummy/guide?id=' . $g->guide_id; ?>">Learn more...</a>
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