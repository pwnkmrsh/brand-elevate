<?php include "../themes/include/header.php"; ?>
<?php
 
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit("session not found");
}

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
            <h1>What do you want to create today?</h1>
        </div>
    </div><!-- End Page Title -->

    <section id="speakers" class="speakers section">
        <!-- Section Title -->
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-4 col-md-12 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

                    <div class="container">
                        <h3>Social Media Post Generator</h3>

                        <form id="postGeneratorForm" class="php-email-form" data-aos="fade-up" data-aos-delay="400">
                            <div class="row gy-3">

                                <div class="col-md-6">
                                    <label>Platform:</label>
                                    <select name="platform" class="form-control" required>
                                        <option value="facebook">Facebook Post</option>
                                        <option value="linkedin">LinkedIn Post</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="youtube">YouTube Community</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Tone:</label>
                                    <select name="tone" class="form-control" required>
                                        <option value="professional">Professional</option>
                                        <option value="funny">Funny</option>
                                        <option value="motivational">Motivational</option>
                                        <option value="creative">Creative</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Describe what post you want:</label>
                                    <textarea name="topic" class="form-control" placeholder="e.g. New product launch, event, motivational message..." required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Generate Image?</label>
                                    <select name="generate_image" class="form-control">
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading alert alert-info">Generating…</div>
                                    <div class="error-message alert alert-danger"></div>
                                    <div class="sent-message alert alert-success">Generated!</div>
                                    <button type="submit" class="btn btn-primary">Generate Post</button>
                                </div>

                            </div>
                        </form>

                        <div id="generatedOutput" class="result-box"></div>
                        <div id="shareButtons" class="mt-3" style="display:none;">
                            <button class="btn btn-secondary" id="copyPost">Copy Text</button>
                            <button class="btn btn-success" id="downloadImage" style="display:none1;">Download Image</button>
                            <a id="waShare" class="btn btn-success" target="_blank">Share on WhatsApp</a>
                            <a id="fbShare" class="btn btn-primary" target="_blank">Share on Facebook</a>
                        </div>

                    </div>
    </section>
</main>

<?php include "../themes/include/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {

        $("#postGeneratorForm").on("submit", function(e) {
            e.preventDefault();

            $(".loading").show();
            $(".error-message").hide();
            $(".sent-message").hide();
            $("#generatedOutput").html("");

            $.ajax({
                url: 'perplexity_api.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                timeout: 45000, // 45s
                success: function(res) {
                    $(".loading").hide();

                    if (res.error) {
                        $(".error-message").text(res.error).show();
                        return;
                    }

                    $(".sent-message").show();

                    var html = '<div class="card p-3"><h5>Generated Post</h5>';
                    html += '<div class="post-text">' + (res.generated_text || '') + '</div>';

                    // 🟢 Show image if exists
                    if (res.image_url) {
                        html += '<img src="' + res.image_url + '" class="img-fluid mt-3" />';
                        $("#downloadImage").show().data("img", res.image_url);
                    }

                    // 🟢 Show all search results
                    if (res.search_results && res.search_results.length > 0) {
                        html += '<h6 class="mt-3">Search Sources</h6>';
                        html += '<ul class="list-group">';

                        res.search_results.forEach(function(item) {
                            html += `
                <li class="list-group-item">
                    <strong>${item.title || 'Result'}</strong><br>
                    <small>${item.url || ''}</small>
                </li>
            `;
                        });

                        html += '</ul>';
                    }

                    // Raw response (optional)
                    if (res.raw) {
                        html += '<details class="mt-3"><summary>Raw response</summary><pre>' + JSON.stringify(res.raw, null, 2) + '</pre></details>';
                    }

                    html += '</div>';
                    $("#generatedOutput").html(html);
                },

                error: function(xhr, status, err) {
                    $(".loading").hide();
                    var message = "Request failed: " + status;
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    }
                    $(".error-message").text(message).show();
                }
            });

        });

    });

    $("#copyPost").click(function() {
        let text = $(".post-text").text();
        navigator.clipboard.writeText(text);
        alert("Copied!");
    });

    $("#downloadImage").click(function() {
        let imgUrl = $(this).data("img");
        const a = document.createElement("a");
        a.href = imgUrl;
        a.download = "post-image.jpg";
        a.click();
    });
</script>
</body>

</html>