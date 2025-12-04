<?php
include "../config/config.php";
include "../themes/include/header.php";
include "../themes/include/menu.php";
?>
<main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <h1>Post Generator</h1>
        </div>
    </div><!-- End Page Title -->

    <section id="speakers" class="speakers section">
        <!-- Section Title -->
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-4 col-md-12 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-6">
                        <form id="postGeneratorForm" class="php-email-form" data-aos="fade-up" data-aos-delay="400">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <label>Platform:</label><br>
                                    <select name="platform" class="form-control" required>
                                        <option value="facebook">Facebook Post</option>
                                        <option value="linkedin">LinkedIn Post</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Describe what post you want:</label><br>
                                    <textarea name="topic" class="form-control" placeholder="e.g. Promotion announcement, motivation post, new product launch..." required></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading" style="display:none;">Loading...</div>
                                    <div class="error-message" style="display:none;"></div>
                                    <div class="sent-message" style="display:none;">Your post is generated!</div>
                                    <button type="submit">Generate Post</button>
                                </div>

                            </div>
                        </form>

                        <!-- Result here -->
                        <div id="generatedOutput" style="margin-top:20px;"></div>


                    </div>
                </div><!-- End Team Member -->
            </div>
        </div>
    </section>
</main>

<?php include "../themes/include/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        $("#postGeneratorForm").on("submit", function(e) {
            e.preventDefault();

            $(".loading").show();
            $(".error-message").hide();
            $(".sent-message").hide();
            $("#generatedOutput").html("");

            $.ajax({
                url: "api.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",

                success: function(response) {
                    $(".loading").hide();

                    if (response.error) {
                        $(".error-message").text(response.error).show();
                    } else {
                        $(".sent-message").show();
                        $("#generatedOutput").html(
                            `<div class='alert alert-success'>${response.generated_text}</div>`
                        );
                    }
                },

                error: function(xhr) {
                    $(".loading").hide();
                    $(".error-message").text("Something went wrong. Try again!").show();
                }
            });

        });

    });
</script>

</body>

</html>