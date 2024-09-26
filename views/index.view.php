<div class="container">
    <?php require base_path("views/includes/head.php") ?>
    <section id="navbar">
        <?php require base_path("views/includes/navbar.php") ?>
    </section>
    <section id="hero">
        <?php require base_path("views/home/sections/hero.php") ?>
    </section>
</div>
<div class="bg-white">
    Some text
    <section id="footer">
        <?php require base_path("views/includes/footer.php") ?>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const fadeInElements = document.querySelectorAll('.fade-in-element');

        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        fadeInElements.forEach(element => {
            observer.observe(element);
        });
    });
</script>