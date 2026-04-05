<?php
/**
 * CMS View for listing all Story Events.
 * @var \App\Models\StoryEvent[] $events
 */
?>
<main class="cms-container container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Stories Management</h1>
        <a href="/cms/stories/homepage" class="btn btn-secondary me-2">
            <i class="bi bi-house-door"></i> Edit Homepage Content
        </a>
        <a href="/cms/stories/edit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Story Event
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Venue</th>
                    <th>Date & Time</th>
                    <th>Language</th>
                    <th>Price / Type</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                <tr>
                    <td colspan="7" class="text-center py-4">No story events found. Click "Add New Story Event" to
                        create one.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= $event->event_id ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($event->name) ?></td>
                    <td><?= htmlspecialchars($event->venue_name ?? 'N/A') ?></td>
                    <td>
                        <?= date('M j, Y', strtotime($event->start_time)) ?><br>
                        <small class="text-muted">
                            <?= date('H:i', strtotime($event->start_time)) ?> -
                            <?= date('H:i', strtotime($event->end_time)) ?>
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-secondary"><?= htmlspecialchars($event->language) ?></span>
                    </td>
                    <td>
                        <?php if ($event->is_pay_as_you_like): ?>
                        <span class="badge bg-success">Pay As You Like</span>
                        <?php else: ?>
                        &euro;<?= number_format((float)$event->price, 2) ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="/cms/stories/edit?id=<?= $event->event_id ?>" class="btn btn-sm btn-warning mb-1"
                            aria-label="Edit <?= htmlspecialchars($event->name) ?>">
                            Edit
                        </a>

                        <form action="/cms/stories/delete" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                            <input type="hidden" name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="id" value="<?= $event->event_id ?>">
                            <button type="submit" class="btn btn-sm btn-danger mb-1"
                                aria-label="Delete <?= htmlspecialchars($event->name) ?>">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>