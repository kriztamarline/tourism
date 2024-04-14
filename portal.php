<style>
        header.masthead {
            background-image: url('<?php echo validate_image($_settings->info('cover')) ?>') !important;
        }
        header.masthead .container {
            background:#0000006b;
        }
    </style>
</head>
<body>
    <!-- Masthead -->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading">Adventure awaits, explore Rangayen</div>
            <div class="masthead-heading text-uppercase">Where</div>
            <a class="btn btn-primary btn-xl text-uppercase" href="#home">View spots</a>
        </div>
    </header>

    <!-- Services -->
   <section class="page-section bg-dark" id="home">
    <div class="container">
        <h2 class="text-center">Rangayen spot</h2>
        <div class="d-flex w-100 justify-content-center">
            <hr class="border-warning" style="border:3px solid" width="15%">
        </div>
        <!-- Add Search Input and Filter Functionality -->
        <div class="d-flex justify-content-center mt-4">
            <input type="text" id="spot-search" class="form-control mr-2" placeholder="Search Rangayen Spots">
            <button id="spot-search-btn" class="btn btn-lg btn-warning border border-dark px-4 py-2 rounded-pill">Search</button>
        </div>
        <div class="row" id="spot-list">
            <?php
            $packages = $conn->query("SELECT * FROM `packages` ORDER BY RAND() LIMIT 3");
            while($row = $packages->fetch_assoc()):
                $cover='';
                if(is_dir(base_app.'uploads/package_'.$row['id'])){
                    $img = scandir(base_app.'uploads/package_'.$row['id']);
                    $k = array_search('.',$img);
                    if($k !== false)
                        unset($img[$k]);
                    $k = array_search('..',$img);
                    if($k !== false)
                        unset($img[$k]);
                    $cover = isset($img[2]) ? 'uploads/package_'.$row['id'].'/'.$img[2] : "";
                }
                $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));

                $review = $conn->query("SELECT * FROM `rate_review` WHERE package_id='{$row['id']}'");
                $review_count =$review->num_rows;
                $rate = 0;
                while($r= $review->fetch_assoc()){
                    $rate += $r['rate'];
                }
                if($rate > 0 && $review_count > 0)
                    $rate = number_format($rate/$review_count,0,"");
            ?>
            <div class="col-md-4 p-4 spot-card">
                <div class="card w-100 rounded-0">
                    <img class="card-img-top" src="<?php echo validate_image($cover) ?>" alt="<?php echo $row['title'] ?>" height="200rem" style="object-fit:cover">
                    <div class="card-body">
                        <h5 class="card-title truncate-1 w-100 spot-title"><?php echo $row['title'] ?></h5><br>
                        <div class="w-100 d-flex justify-content-start">
                            <div class="stars stars-small">
                                <input disabled class="star star-5" id="star-5" type="radio" name="star" <?php echo $rate == 5 ? "checked" : '' ?>/> <label class="star star-5" for="star-5"></label> 
                                <input disabled class="star star-4" id="star-4" type="radio" name="star" <?php echo $rate == 4 ? "checked" : '' ?>/> <label class="star star-4" for="star-4"></label> 
                                <input disabled class="star star-3" id="star-3" type="radio" name="star" <?php echo $rate == 3 ? "checked" : '' ?>/> <label class="star star-3" for="star-3"></label> 
                                <input disabled class="star star-2" id="star-2" type="radio" name="star" <?php echo $rate == 2 ? "checked" : '' ?>/> <label class="star star-2" for="star-2"></label> 
                                <input disabled class="star star-1" id="star-1" type="radio" name="star" <?php echo $rate == 1 ? "checked" : '' ?>/> <label class="star star-1" for="star-1"></label> 
                            </div>
                        </div>
                        <p class="card-text truncate spot-description"><?php echo $row['description'] ?></p>
                        <div class="w-100 d-flex justify-content-end">
                            <a href="./?page=view_package&id=<?php echo md5($row['id']) ?>" class="btn btn-sm btn-flat btn-warning">View Spot <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="d-flex w-100 justify-content-end">
            <a href="./?page=packages" class="btn btn-flat btn-warning mr-4">Explore? Click here. <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const spotSearchInput = document.getElementById('spot-search');
        const spotSearchButton = document.getElementById('spot-search-btn');
        const spotListContainer = document.getElementById('spot-list');

        // Function to filter spots based on search input
        function filterSpots() {
            const searchTerm = spotSearchInput.value.toLowerCase();
            const spots = document.querySelectorAll('.spot-card');
            
            spots.forEach(function(spot) {
                const title = spot.querySelector('.spot-title').innerText.toLowerCase();
                const description = spot.querySelector('.spot-description').innerText.toLowerCase();
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    spot.style.display = 'block';
                } else {
                    spot.style.display = 'none';
                }
            });
        }

        // Event listener for search button click
        spotSearchButton.addEventListener('click', filterSpots);

        // Event listener for search input change
        spotSearchInput.addEventListener('input', filterSpots);

        // Initial filtering on page load
        filterSpots();
    });
</script>

    <!-- About -->
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">About Us</h2>
            </div>
            <div>
                <div class="card w-100">
                    <div class="card-body">
                        <?php echo file_get_contents(base_app.'about.html') ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="page-section" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Thank you for visiting our website</h2>
                <h3 class="section-subheading text-muted"></h3>
            </div>
        </div>
    </section>
