
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="icon" type="images/png" href="logo1.png">
    <link rel="stylesheet" href="homePage .css">

    
</head>
<body>
    
    <div class="hamburger" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    

    <div id="overlay" class="overlay" onclick="closeNav()"></div>


    <nav id="sideNav" class="side-nav">
       
        <img src="images/logo1.png" alt="Logo" class="nav-logo">
        <a href="Admin/AdminLoginPage.php">Admin Login</a>
        <a href="Student/StudentLoginPage.php">Student Login</a>
        <a href="Teacher/TeacherLoginPage.php">Teacher Login</a>
    </nav>

    <div class="container">
        <div class="animated-text">
            Shaping <span class="highlight">learners</span> who inspire the world
        </div>
    </div>

    <div class="headline">
        Find your place
        <div class="content-wrapper">
            <p>
                An economically independent and vibrant community that consistently provides the highest standards of excellence and innovation in learning within a stimulating nurturing environment.
            </p>
        </div>
    </div>

    <div class="testimonial-container">
        <div class="testimonial-main">
            <img id="testimonialImage" src="images/image2.jpg" alt="Community Testimonial" style="width: 60%; max-width: 500px;    border-radius: 15px; 
            ">
            <div class="testimonial-text" id="testimonialText"> Horizon Academy is comprehensive and the teaching is supportive.</div>
        </div>
        <div class="image-gallery">
            <img class="gallery-img" src="images/IMG_1908.PNG" alt="Thumbnail 1" data-index="0">
            <img class="gallery-img" src="images/IMG_1909.PNG" alt="Thumbnail 2" data-index="1">
            <img class="gallery-img" src="images/IMG_1910.PNG" alt="Thumbnail 3" data-index="2">
        </div>
    </div>

    <div id="scrollDownArrow" class="scroll-down-arrow">
        &#x21E3; 
    </div>
    
</body>






<script>
document.addEventListener('DOMContentLoaded', () => {
    const animatedText = document.querySelector('.animated-text');
    const originalText = 'Shaping <span class="highlight">learners</span> who inspire the world'; 
    const newText = 'Horizon Academy <span class="highlight"> Blackboard </span>'; 

    function revealText() {
        const scrollPosition = window.scrollY;
        const revealPosition = animatedText.offsetTop - window.innerHeight + 200;

        if (scrollPosition >= revealPosition) {
            animatedText.innerHTML = newText;
        } else {
            animatedText.innerHTML = originalText;
        }
    }

    window.addEventListener('scroll', revealText);
});


function toggleMenu() {
    var nav = document.getElementById('sideNav');
    var overlay = document.getElementById('overlay');
    if (nav.style.width === '250px') {
        nav.style.width = '0';
        overlay.style.display = "none";
    } else {
        nav.style.width = '250px';
        overlay.style.display = "block";
    }
}


    function openNav() {
    document.getElementById('sideNav').style.width = "250px"; 
    document.getElementById('overlay').style.display = "block"; 
}

function closeNav() {
    document.getElementById('sideNav').style.width = "0";
    document.getElementById('overlay').style.display = "none"; 
}



document.addEventListener('DOMContentLoaded', () => {
    const testimonials = [
        { img: 'images/image2.jpg', text: ' Horizon Academy is comprehensive and the teaching is supportive.' },
        { img: 'images/image1.jpg', text: ' Horizon Academy is a community with supporting teachers and an engaging curriculim.' },
        { img: 'images/image3.jpg', text: ' The teachers have our best interests in mind and are helping us to thrive' },
    ];

    const galleryImages = document.querySelectorAll('.gallery-img');
    const testimonialImage = document.getElementById('testimonialImage');
    const testimonialText = document.getElementById('testimonialText');

    galleryImages.forEach(image => {
        image.addEventListener('click', () => {
            const index = image.getAttribute('data-index');
            testimonialImage.src = testimonials[index].img;
            testimonialText.innerHTML = testimonials[index].text;
        });
    });
});

function openNav() {
    document.getElementById('sideNav').style.width = "100%"; 
    document.getElementById('sideNav').classList.add('open');
}


function closeNav() {
    document.getElementById('sideNav').style.width = "0";
    document.getElementById('sideNav').classList.remove('open');
}

window.addEventListener('scroll', () => {
    const scrollArrow = document.getElementById('scrollDownArrow');
    if (window.scrollY > 100) { 
        scrollArrow.style.display = 'none';
    } else {
        scrollArrow.style.display = 'block';
    }
});

    
    
</script>
</html>