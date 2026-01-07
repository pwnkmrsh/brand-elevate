<?php include "../themes/include/header.php"; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
} 


require_once 'functions.php'; // if function is separate

$posts = getSocialPosts($pdo, 50);
?> 

<style>
    label {
        color: #000000;
    }
</style>

<main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <h1>All Collections</h1>
        </div>
    </div><!-- End Page Title -->

    <section id="speakers" class="speakers section">
        <!-- Section Title -->
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-4 col-md-12 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

                    <div class="container">
                         
 
<?php if (!$posts): ?>
        <div class="alert alert-warning">No posts found</div>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <span class="badge bg-primary"><?= ucfirst($post['platform']) ?></span>
                    <small class="text-muted"><?= date('d M Y, h:i A', strtotime($post['created_at'])) ?></small>
                </div>

                <h6 class="mt-2"><?= htmlspecialchars($post['topic']) ?></h6>

                <p class="mt-2">
                    <?= nl2br(htmlspecialchars($post['post_text'])) ?>
                </p>

                <?php if (!empty($post['image_url'])): ?>
                    <img src="<?= $post['image_url'] ?>" class="img-fluid rounded mt-2">
                <?php endif; ?>
 <div class="mt-3">
    <span class="badge bg-secondary">Tone: <?= htmlspecialchars($post['tone']) ?></span>

    <div class="mt-3 share-buttons"
         data-text="<?= htmlspecialchars($post['post_text'], ENT_QUOTES) ?>"
         data-platform="<?= htmlspecialchars($post['platform']) ?>">

        <button class="btn btn-sm btn-outline-dark copy-btn">
            📋 Copy Text
        </button>

        <a class="btn btn-sm btn-success wa-share" target="_blank">
            🟢 WhatsApp
        </a>

        <a class="btn btn-sm btn-primary platform-share" target="_blank">
            🔗 Share
        </a>
    </div>
</div>


            </div>
        </div>
    <?php endforeach; ?>
                    </div>
    </section>
</main>

<?php include "../themes/include/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // COPY TEXT
    document.querySelectorAll(".copy-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            const text = this.closest(".share-buttons").dataset.text;

            navigator.clipboard.writeText(text).then(() => {
                btn.innerText = "✅ Copied";
                setTimeout(() => btn.innerText = "📋 Copy Text", 1500);
            });
        });
    });

    // WHATSAPP SHARE
    document.querySelectorAll(".wa-share").forEach(btn => {
        const text = btn.closest(".share-buttons").dataset.text;
        btn.href = "https://wa.me/?text=" + encodeURIComponent(text);
    });

    // PLATFORM SHARE
    document.querySelectorAll(".platform-share").forEach(btn => {
        const wrapper = btn.closest(".share-buttons");
        const text = wrapper.dataset.text;
        const platform = wrapper.dataset.platform.toLowerCase();

        let url = "#";

        switch (platform) {
            case "facebook":
                url = "https://www.facebook.com/sharer/sharer.php?u=&quote=" + encodeURIComponent(text);
                btn.innerText = "🔵 Facebook";
                break;

            case "twitter":
            case "x":
                url = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(text);
                btn.innerText = "❌ X";
                break;

            case "linkedin":
                url = "https://www.linkedin.com/sharing/share-offsite/?summary=" + encodeURIComponent(text);
                btn.innerText = "🔗 LinkedIn";
                break;

            case "instagram":
                btn.innerText = "📸 Instagram";
                url = "#";
                btn.onclick = () => alert("Instagram does not support direct web sharing. Copy text and post manually.");
                break;
        }

        btn.href = url;
    });

});
</script>


</html>