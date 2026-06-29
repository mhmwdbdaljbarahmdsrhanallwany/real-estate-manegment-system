document.querySelectorAll('.nav-links-horizontal .nav-link').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.nav-links-horizontal .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});