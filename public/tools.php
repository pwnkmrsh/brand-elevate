<?php
include "../config/config.php";
include "../themes/include/header.php";
include "../themes/include/menu.php";
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
                    <div class="col-lg-6">

                        <form id="postGeneratorForm" class="php-email-form">

                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <label>Platform:</label>
                                    <select name="platform" class="form-control" required>
                                        <option value="facebook">Facebook</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="youtube">YouTube Community Post</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Tone:</label>
                                    <select name="tone" class="form-control" required>
                                        <option value="professional">Professional</option>
                                        <option value="funny">Funny</option>
                                        <option value="emotional">Emotional</option>
                                        <option value="motivational">Motivational</option>
                                        <option value="creative">Creative</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Word Limit:</label>
                                    <input type="number" name="words" class="form-control" placeholder="400 words" min="20" max="400">
                                </div>

                                <div class="col-md-6">
                                    <label>Generate Hashtags?</label>
                                    <select name="hashtags" class="form-control">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Describe your Post:</label>
                                    <textarea name="topic" class="form-control" required></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label>Generate Image?</label>
                                    <select name="image" class="form-control">
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="submit">Generate</button>
                                </div>
                            </div>
                        </form>

                        <div id="generatedResult" class="mt-3"></div>



                    </div>
                </div><!-- End Team Member -->
            </div>
        </div>
    </section>
</main>

<?php include "../themes/include/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $("#postGeneratorForm").on("submit", function(e) {
        e.preventDefault();

        $("#generatedResult").html("<div class='alert alert-info'>Generating...</div>");

        $.ajax({
            url: "api.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            success: function(res) {
                if (res.error) {
                    $("#generatedResult").html("<div class='alert alert-danger'>" + res.error + "</div>");
                } else {
                    let html = `<div class="card p-3">
                        <h5>Generated Post:</h5>
                        <div>${res.post}</div> `;
                    if (res.image) {
                        html += `<img src="${res.image}" class="img-fluid mt-3" />`;
                    }
                    html += `
                        <br><a href="${res.pdf}" class="btn btn-dark mt-3">Download PDF</a>
                        <a onclick="postToFacebook('${res.post}')" class="btn btn-primary mt-3">Auto Post to Facebook</a>
                    </div>`;

                    $("#generatedResult").html(html);
                }
            }

        });
    });
</script>

</body>

</html>