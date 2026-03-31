<?php
/**
 * Partial view for the Audio Preview and Transcript Toggle
 * Requires $event object to be passed in.
 */

// Generate a unique ID for the collapse element in case multiple players are on the same page
$uniqId = $event->event_id ?? uniqid();
$collapseId = 'audioTranscriptCollapse_' . $uniqId;
$audioId = 'audio-el-' . $uniqId;
$playBtnId = 'play-btn-' . $uniqId;
$sliderId = 'audio-slider-' . $uniqId;
$timerId = 'audio-timer-' . $uniqId;?>

<div class="stories-audio-section">
    <div class="stories-audio-header">
        <p class="stories-audio-label">AUDIO PREVIEW</p>
        <span class="stories-audio-timer" id="<?= $timerId ?>">0:00 / 0:00</span>
    </div>

    <div class="stories-audio-player-container">
        <p class="stories-audio-title-text">
            <?= !empty($event->audio_title) ? htmlspecialchars($event->audio_title) : htmlspecialchars($event->name ?? 'Story') . ' - Preview' ?>
        </p>

        <div class="stories-custom-player">
            <button class="stories-play-btn" id="<?= $playBtnId ?>" aria-label="Play/Pause">▶</button>
            <input type="range" class="stories-audio-slider" id="<?= $sliderId ?>" value="0" min="0" step="0.1"
                aria-label="Audio progress">
        </div>

        <audio id="<?= $audioId ?>" preload="metadata">
            <source
                src="<?= !empty($event->audio_preview_path) ? htmlspecialchars($event->audio_preview_path) : '/assets/audio/placeholder.mp3' ?>"
                type="audio/mpeg">
            Your browser does not support audio playback.
        </audio>

        <div class="stories-transcript-toggle">
            <a data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button" aria-expanded="false"
                aria-controls="<?= $collapseId ?>">
                View transcribed text ▼
            </a>
        </div>

        <div class="collapse mt-3" id="<?= $collapseId ?>">
            <div class="card card-body stories-transcript-box">
                <?= !empty($event->audio_transcript) ? nl2br(htmlspecialchars($event->audio_transcript)) : "Welcome to the audio preview of " . htmlspecialchars($event->name ?? 'this story') . "! The Haarlem Festival is dedicated to bringing history to life. (This is a placeholder transcribed text. The actual transcript for this specific audio clip will be provided soon.)" ?>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    document.addEventListener("DOMContentLoaded", function() {
        const audio = document.getElementById("<?= $audioId ?>");
        const playBtn = document.getElementById("<?= $playBtnId ?>");
        const slider = document.getElementById("<?= $sliderId ?>");
        const timer = document.getElementById("<?= $timerId ?>");

        // Helper to format time in M:SS
        function formatTime(seconds) {
            if (isNaN(seconds) || !isFinite(seconds)) return "0:00";
            let min = Math.floor(seconds / 60);
            let sec = Math.floor(seconds % 60);
            if (sec < 10) sec = "0" + sec;
            return min + ":" + sec;
        }

        // Initialize timer and slider when metadata loads
        audio.addEventListener("loadedmetadata", function() {
            slider.max = audio.duration;
            timer.innerText = "0:00 / " + formatTime(audio.duration);
        });

        // Toggle Play/Pause
        playBtn.addEventListener("click", function() {
            if (audio.paused) {
                audio.play();
                playBtn.innerHTML = "⏸"; // Pause icon
                playBtn.classList.add("playing");
            } else {
                audio.pause();
                playBtn.innerHTML = "▶"; // Play icon
                playBtn.classList.remove("playing");
            }
        });

        // Update slider and timer as audio plays
        audio.addEventListener("timeupdate", function() {
            slider.value = audio.currentTime;
            timer.innerText = formatTime(audio.currentTime) + " / " + formatTime(audio.duration);
        });

        // Seek when user drags slider
        slider.addEventListener("input", function() {
            audio.currentTime = slider.value;
        });

        // Reset button when audio ends
        audio.addEventListener("ended", function() {
            playBtn.innerHTML = "▶";
            playBtn.classList.remove("playing");
            slider.value = 0;
            timer.innerText = "0:00 / " + formatTime(audio.duration);
        });
    });
})();
</script>