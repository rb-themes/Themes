document.addEventListener('DOMContentLoaded', (event) => {
    var check_elementor = typeof elementorFrontend != 'undefined' ? elementorFrontend.isEditMode() : true;
    if (navigator.platform.indexOf("Win") != -1 && !check_elementor && (typeof elementorAdmin == 'undefined')) {
        smoothScroll();
    }
});

function smoothScroll() {
    var ease = 0.1;
    var currentScroll = window.scrollY;
    var targetScroll = currentScroll;
    var isScrolling = false;

    window.addEventListener('wheel', (e) => {

        targetScroll += e.deltaY;
        if (targetScroll <= 0) {
            targetScroll = 0;
        }

        if (targetScroll >= document.documentElement.scrollHeight - window.innerHeight) {
            targetScroll = document.documentElement.scrollHeight - window.innerHeight;
        }

        if (!isScrolling) {
            window.requestAnimationFrame(scrollToTarget);
            isScrolling = true;
        }
        e.preventDefault();
    }, {passive: false});

    window.addEventListener('scroll', () => {
        if (!isScrolling) {
            targetScroll = window.scrollY;
            currentScroll = window.scrollY;
        }
    });

    function scrollToTarget() {
        currentScroll += (targetScroll - currentScroll) * ease;
        if (Math.abs(targetScroll - currentScroll) < 0.5) {
            window.scrollTo(0, targetScroll);
            isScrolling = false;
            return;
        }
        window.scrollTo(0, currentScroll);
        window.requestAnimationFrame(scrollToTarget);
    }
}
