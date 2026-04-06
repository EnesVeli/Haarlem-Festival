<?php
/**
 * CMS Stories Homepage editor form.
 *
 * Variables via extract($data):
 *   CmsStoriesHomepageViewModel $viewModel
 *   string                      $pageTitle
 *
 * @var \App\ViewModels\CmsStoriesHomepageViewModel $viewModel
 */
$c = $viewModel->content;
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="mb-4"><?= htmlspecialchars($viewModel->pageTitle) ?></h1>

            <?php if ($viewModel->success): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($viewModel->success) ?></div>
            <?php endif; ?>
            <?php if ($viewModel->error): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($viewModel->error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/cms/stories/homepage" enctype="multipart/form-data"
                aria-label="Edit Stories homepage content">

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($viewModel->csrfToken) ?>">

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control"
                        value="<?= htmlspecialchars($c->title ?? '') ?>" aria-label="Page title" required>
                </div>

                <!-- Subtitle -->
                <div class="mb-3">
                    <label for="subtitle" class="form-label">Subtitle</label>
                    <input type="text" id="subtitle" name="subtitle" class="form-control"
                        value="<?= htmlspecialchars($c->subtitle ?? '') ?>" aria-label="Page subtitle">
                </div>

                <!-- Body HTML (WYSIWYG) -->
                <div class="mb-3">
                    <label for="body_html" class="form-label">Body Content (HTML)</label>
                    <textarea id="body_html" name="body_html" class="form-control wysiwyg" rows="10"
                        aria-label="Body HTML content"><?= htmlspecialchars($c->body_html ?? '') ?></textarea>
                </div>

                <!-- Quote Text -->
                <div class="mb-3">
                    <label for="quote_text" class="form-label">Quote Text</label>
                    <input type="text" id="quote_text" name="quote_text" class="form-control"
                        value="<?= htmlspecialchars($c->quote_text ?? '') ?>" aria-label="Quote text">
                </div>

                <!-- CTA Text -->
                <div class="mb-3">
                    <label for="cta_text" class="form-label">Call-to-Action Text</label>
                    <input type="text" id="cta_text" name="cta_text" class="form-control"
                        value="<?= htmlspecialchars($c->cta_text ?? '') ?>" aria-label="Call to action text">
                </div>

                <h5 class="mt-4">Ticket Info - Card 1 (Pay as you like)</h5>
                <div class="mb-3">
                    <label class="form-label" for="ticket_info_title_1">Card 1 Title</label>
                    <input type="text" id="ticket_info_title_1" name="ticket_info_title_1" class="form-control"
                        value="<?= htmlspecialchars($c->ticket_info_title_1 ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ticket_info_body_1">Card 1 Body Text</label>
                    <textarea id="ticket_info_body_1" name="ticket_info_body_1" class="form-control" rows="4"><?= htmlspecialchars($c->ticket_info_body_1 ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ticket_info_note_1">Card 1 Note (italic line)</label>
                    <input type="text" id="ticket_info_note_1" name="ticket_info_note_1" class="form-control"
                        value="<?= htmlspecialchars($c->ticket_info_note_1 ?? '') ?>">
                </div>

                <h5 class="mt-4">Ticket Info - Card 2 (HaarlemPas)</h5>
                <div class="mb-3">
                    <label class="form-label" for="ticket_info_title_2">Card 2 Title</label>
                    <input type="text" id="ticket_info_title_2" name="ticket_info_title_2" class="form-control"
                        value="<?= htmlspecialchars($c->ticket_info_title_2 ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ticket_info_body_2">Card 2 Body Text</label>
                    <textarea id="ticket_info_body_2" name="ticket_info_body_2" class="form-control" rows="3"><?= htmlspecialchars($c->ticket_info_body_2 ?? '') ?></textarea>
                </div>

                <h5 class="mt-4">CTA Banner (bottom of page)</h5>
                <div class="mb-3">
                    <label class="form-label" for="cta_description">CTA Description Text</label>
                    <textarea id="cta_description" name="cta_description" class="form-control" rows="2"><?= htmlspecialchars($c->cta_description ?? '') ?></textarea>
                </div>

                <!-- Hero Image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Hero Image</label>
                    <?php if (!empty($c->image_path)): ?>
                    <div class="mb-2">
                        <img src="<?= htmlspecialchars($c->image_path) ?>" alt="Current hero image"
                            class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    <input type="hidden" name="existing_image_path" value="<?= htmlspecialchars($c->image_path) ?>">
                    <?php endif; ?>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*"
                        aria-label="Upload hero image">
                    <div class="form-text">Leave empty to keep the current image.</div>
                </div>

                <!-- Submit -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" aria-label="Save changes">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="/cms" class="btn btn-outline-secondary" aria-label="Back to CMS dashboard">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- TinyMCE WYSIWYG init -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    tinymce.init({
        selector: '.wysiwyg',
        height: 350,
        menubar: false,
        plugins: 'lists link image code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
        branding: false
    });
});
</script>
