<?php
/**
 * Partial view for the Audio Preview player + transcript toggle.
 * Requires $event object to be passed in.
 */

$uniqId = $event->event_id ?? uniqid('story_audio_', true);
$audioId = 'audio-el-' . $uniqId;
$playBtnId = 'audio-play-btn-' . $uniqId;
$sliderId = 'audio-slider-' . $uniqId;
$timerId = 'audio-timer-' . $uniqId;
$toggleId = 'audio-transcript-toggle-' . $uniqId;
$contentId = 'audio-transcript-content-' . $uniqId;

$audioTitle = !empty($event->audio_title)
    ? (string)$event->audio_title
    : ((string)($event->name ?? 'Story') . ' - Preview');

$audioSource = !empty($event->audio_preview_path)
    ? (string)$event->audio_preview_path
    : '/assets/audio/placeholder.mp3';

$transcriptText = !empty($event->audio_transcript)
    ? nl2br(htmlspecialchars((string)$event->audio_transcript))
    : 'Transcript not available yet for this audio preview.';
?>

<div class="stories-audio-preview" data-audio-preview="<?= htmlspecialchars((string)$uniqId) ?>">
    <div class="stories-audio-preview__head">
        <p class="stories-audio-preview__label">AUDIO PREVIEW</p>
        <span class="stories-audio-preview__timer" id="<?= htmlspecialchars($timerId) ?>">00:00 / 01:30</span>
    </div>

    <div class="stories-audio-preview__controls">
        <button type="button" class="stories-audio-preview__play" id="<?= htmlspecialchars($playBtnId) ?>"
            aria-label="Play audio">
            <span class="stories-audio-preview__icon stories-audio-preview__icon--play" aria-hidden="true"></span>
        </button>

        <input type="range" class="stories-audio-preview__slider" id="<?= htmlspecialchars($sliderId) ?>" value="0"
            min="0" max="100" step="0.1" aria-label="Audio progress">
    </div>

    <p class="stories-audio-preview__title">"<?= htmlspecialchars($audioTitle) ?>"</p>

    <button type="button" class="stories-audio-preview__toggle" id="<?= htmlspecialchars($toggleId) ?>"
        aria-expanded="false" aria-controls="<?= htmlspecialchars($contentId) ?>">
        <span class="stories-audio-preview__chevron" aria-hidden="true"></span>
        <span>View Transcribed Text</span>
    </button>

    <div class="stories-audio-preview__transcript" id="<?= htmlspecialchars($contentId) ?>" hidden>
        <?= $transcriptText ?>
    </div>

    <audio id="<?= htmlspecialchars($audioId) ?>" preload="metadata">
        <source src="<?= htmlspecialchars($audioSource) ?>" type="audio/mpeg">
        Your browser does not support audio playback.
    </audio>
</div>

<?php static $audioPreviewStylesPrinted = false; ?>
<?php if (!$audioPreviewStylesPrinted): ?>
<?php $audioPreviewStylesPrinted = true; ?>
<style>
.stories-audio-preview {
    border: 1px solid #e5e7eb;
    border-left: 4px solid #9f1d1d;
    border-radius: 10px;
    background: #f7f8fa;
    padding: 14px 16px 10px;
    margin-top: 1.5rem;
}
.stories-audio-preview__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.stories-audio-preview__label {
    margin: 0;
    font-size: 0.74rem;
    letter-spacing: 0.09em;
    font-weight: 800;
    color: #9f1d1d;
}
.stories-audio-preview__timer {
    font-size: 0.75rem;
    color: #c0c4cc;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}
.stories-audio-preview__controls {
    display: grid;
    grid-template-columns: 34px 1fr;
    gap: 10px;
    align-items: center;
}
.stories-audio-preview__play {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 2px solid #d8aa3d;
    background: #8f969f;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0;
}
.stories-audio-preview__icon--play {
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    border-left: 10px solid #9f1d1d;
    margin-left: 2px;
}
.stories-audio-preview__play.is-playing .stories-audio-preview__icon--play {
    width: 10px;
    height: 12px;
    border: 0;
    margin-left: 0;
    background:
        linear-gradient(to right, #1f2937 0 3px, transparent 3px 7px, #1f2937 7px 10px);
}
.stories-audio-preview__slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 16px;
    background: transparent;
    cursor: pointer;
}
.stories-audio-preview__slider::-webkit-slider-runnable-track {
    height: 16px;
    border-radius: 999px;
    background: linear-gradient(to right, #3478f6 var(--played, 0%), #d7d9dd var(--played, 0%));
}
.stories-audio-preview__slider::-moz-range-track {
    height: 16px;
    border-radius: 999px;
    background: #d7d9dd;
}
.stories-audio-preview__slider::-moz-range-progress {
    height: 16px;
    border-radius: 999px;
    background: #3478f6;
}
.stories-audio-preview__slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    margin-top: 1px;
    width: 14px;
    height: 14px;
    border-radius: 999px;
    border: 0;
    background: #3478f6;
}
.stories-audio-preview__slider::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 999px;
    border: 0;
    background: #3478f6;
}
.stories-audio-preview__title {
    margin: 10px 0 8px;
    font-size: 1.03rem;
    color: #141414;
}
.stories-audio-preview__toggle {
    width: 100%;
    border: 0;
    border-top: 1px solid #e0e3e8;
    background: transparent;
    padding: 10px 0 2px;
    color: #6b7280;
    font-size: 1rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    text-align: left;
}
.stories-audio-preview__chevron {
    width: 7px;
    height: 7px;
    border-right: 1.8px solid #6b7280;
    border-bottom: 1.8px solid #6b7280;
    transform: rotate(-45deg);
    margin-top: -1px;
    transition: transform 0.2s ease;
}
.stories-audio-preview__toggle[aria-expanded="true"] .stories-audio-preview__chevron {
    transform: rotate(45deg);
}
.stories-audio-preview__transcript {
    margin-top: 9px;
    line-height: 1.6;
    font-size: 0.95rem;
    color: #1f2937;
}
.stories-audio-preview__transcript strong {
    color: #9f1d1d;
}
</style>
<?php endif; ?>

<script>
(function() {
    const audio = document.getElementById("<?= $audioId ?>");
    const playBtn = document.getElementById("<?= $playBtnId ?>");
    const slider = document.getElementById("<?= $sliderId ?>");
    const timer = document.getElementById("<?= $timerId ?>");
    const toggle = document.getElementById("<?= $toggleId ?>");
    const transcript = document.getElementById("<?= $contentId ?>");

    function formatTime(totalSeconds) {
        if (!Number.isFinite(totalSeconds) || totalSeconds < 0) {
            return "00:00";
        }
        const mins = Math.floor(totalSeconds / 60);
        const secs = Math.floor(totalSeconds % 60);
        return String(mins).padStart(2, "0") + ":" + String(secs).padStart(2, "0");
    }

    function updateTimer() {
        const current = audio.currentTime || 0;
        const total = Number.isFinite(audio.duration) ? audio.duration : 0;
        timer.textContent = formatTime(current) + " / " + formatTime(total);
    }

    function updateSliderFill() {
        const max = Number(slider.max) || 0;
        const current = Number(slider.value) || 0;
        const percentage = max > 0 ? Math.min(100, (current / max) * 100) : 0;
        slider.style.setProperty("--played", percentage + "%");
    }

    function syncPlayerState() {
        const isPlaying = !audio.paused;
        playBtn.classList.toggle("is-playing", isPlaying);
        playBtn.setAttribute("aria-label", isPlaying ? "Pause audio" : "Play audio");
    }

    function setDurationFromMetadata() {
        const total = Number.isFinite(audio.duration) && audio.duration > 0 ? audio.duration : 0;
        slider.max = String(total);
        slider.value = String(Math.min(audio.currentTime || 0, total));
        updateSliderFill();
        updateTimer();
    }

    audio.addEventListener("loadedmetadata", setDurationFromMetadata);
    audio.addEventListener("durationchange", setDurationFromMetadata);

    audio.addEventListener("timeupdate", function() {
        slider.value = String(audio.currentTime || 0);
        updateSliderFill();
        updateTimer();
    });

    audio.addEventListener("play", syncPlayerState);
    audio.addEventListener("pause", syncPlayerState);
    audio.addEventListener("ended", function() {
        slider.value = "0";
        updateSliderFill();
        updateTimer();
        syncPlayerState();
    });

    playBtn.addEventListener("click", function() {
        if (audio.paused) {
            const playPromise = audio.play();
            if (playPromise && typeof playPromise.catch === "function") {
                playPromise.catch(function() {});
            }
        } else {
            audio.pause();
        }
    });

    slider.addEventListener("input", function() {
        const to = Number(slider.value) || 0;
        if (Number.isFinite(audio.duration)) {
            audio.currentTime = Math.min(Math.max(0, to), audio.duration);
        } else {
            audio.currentTime = Math.max(0, to);
        }
        updateSliderFill();
        updateTimer();
    });

    toggle.addEventListener("click", function() {
        const isExpanded = toggle.getAttribute("aria-expanded") === "true";
        toggle.setAttribute("aria-expanded", isExpanded ? "false" : "true");
        transcript.hidden = isExpanded;
    });

    setDurationFromMetadata();
    syncPlayerState();
})();
</script>
