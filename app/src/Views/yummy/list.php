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

<main>
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
                        <button><? echo $t['name'] ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

            <div>
                <div>Meal Type:</div>
                <? if(count($ml_types) > 0): ?>
                    <? foreach($ml_types as $t): ?>
                        <button><? echo $t['name'] ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            
            <div>
                <div>Food Type:</div>
                <? if(count($fd_types) > 0): ?>
                    <? foreach($fd_types as $t): ?>
                        <button><? echo $t['name'] ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>        

            <div>
                <div>Cuisine:</div>
                <? if(count($cs_types) > 0): ?>
                    <? foreach($cs_types as $t): ?>
                        <button><? echo $t['name'] ?></button>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
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
        <div>

        </div>
        <div></div>
        <div>

        </div>
    </section>
</main>

<?php require '/app/src/Views/partials/footer.php'; ?>