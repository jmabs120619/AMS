<!-- loading_screen.php -->
<div id="loadingScreen" class="preloader">
    <div id="loadingSpinner">
        <img src="DPWH_Logo.jpg" alt="DPWH Logo" id="logoSpinner">
    </div>
</div>

<style>
    /* Loading screen style */
    #loadingScreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent background */
        display: flex;
        /* Show the loading screen initially */
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Makes sure it's on top */
        opacity: 1;
        /* Fully visible */
        transition: opacity 0.5s ease-out;
        /* For fade-out effect */
    }

    #loadingSpinner {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        /* Adjust the size of the spinner */
        height: 50px;
    }

    #logoSpinner {
        width: 100%;
        /* Make the logo fill the container */
        height: 100%;
        animation: spin 2s linear infinite;
        /* Apply the spin animation */
    }

    /* Spinner animation */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var $window = $(window);
    $window.on("load", function() {
        // Preloader fade out effect after a slight delay to ensure everything is loaded
        setTimeout(function() {
            $("#loadingScreen").fadeOut(500); // Fade out the loading screen after it has fully loaded
            $("#logoSpinner").css("animation", "none"); // Stop the spinning animation
        }, 500); // Delay the fade-out by 500ms to ensure everything loads correctly
    });

    // Optionally, if you want to show the loading screen on every page refresh
    window.addEventListener('load', function() {
        $("#loadingScreen").fadeIn(500); // Show the loading screen
    });
</script>