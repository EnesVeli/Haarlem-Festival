<?php
/** @var ?\App\ViewModels\TicketsCategoryBaseViewModel $view_model */
?>
<header class="tickets-header">
    <h1 id="tickets-heading">Festival Tickets</h1>
    <p class="tickets-subtitle">Select a category to explore events and book your tickets.</p>
</header>

<? if(!isset($view_model)): ?>
    <nav class="tickets-tabs" aria-label="Event categories">    
        <a href="/tickets/history" class="tickets-tab" aria-label="History tickets">History</a>
        <a href="/tickets/stories" class="tickets-tab" aria-label="Stories tickets">Stories</a>
        <a href="/tickets/yummy" class="tickets-tab" aria-label="Yummy tickets">Yummy</a>
        <a href="/tickets/jazz" class="tickets-tab" aria-label="Haarlem Jazz tickets">Jazz</a>   
    </nav>
<? else: ?>
    <nav class="tickets-tabs" aria-label="Event categories">
        <a href="/tickets/history" class="tickets-tab <?= $view_model->categoryKey === 'history' ? 'tickets-tab--active' : '' ?>" aria-label="History tickets">History</a>
        <a href="/tickets/stories" class="tickets-tab <?= $view_model->categoryKey === 'stories' ? 'tickets-tab--active' : '' ?>" aria-label="Stories tickets">Stories</a>
        <a href="/tickets/yummy" class="tickets-tab <?= $view_model->categoryKey === 'yummy' ? 'tickets-tab--active' : '' ?>" aria-label="Yummy tickets">Yummy</a>
        <a href="/tickets/jazz" class="tickets-tab <?= $view_model->categoryKey === 'jazz' ? 'tickets-tab--active' : '' ?>" aria-label="Jazz tickets">Jazz</a>
    </nav>
<? endif; ?>