<?php
/** @var ?App\ViewModels\CmsOrderListViewModel $view_model */
?>

<? if($view_model->total_page_number !== null && $view_model->total_page_number > 1): ?>
    <div class="tickets-pages-container">
        <? if($view_model->current_page !== 1): ?>
            <? if($view_model->current_page !== 2): ?>
                <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination(1) ?>">&lt;&lt;</a>
            <? endif; ?>
            <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination($view_model->current_page - 1)  ?>">&lt;</a>
        <? endif; ?>

        <? for($i = $view_model->page_offset_left; $i < $view_model->current_page; $i++): ?>
            <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination($i) ?>"><?= $i ?></a>
        <? endfor; ?>

        <a class="ticket-page-btn ticket-page-btn__sel" href="<?= $view_model->getUrlForPagination($view_model->current_page) ?>"><?= $view_model->current_page ?></a>

        <? for($i = $view_model->current_page + 1; $i < $view_model->page_offset_right + 1; $i++): ?>
            <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination($i) ?>"><?= $i ?></a>
        <? endfor; ?>

        <? if($view_model->current_page !== $view_model->total_page_number): ?>         
            <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination($view_model->current_page + 1) ?>">&gt;</a>
            <? if($view_model->current_page !== $view_model->total_page_number - 1): ?>    
                <a class="ticket-page-btn ticket-page-btn__not-sel" href="<?= $view_model->getUrlForPagination($view_model->total_page_number) ?>">&gt;&gt;</a>
            <? endif; ?>
        <? endif; ?>
    </div>
<? endif; ?>