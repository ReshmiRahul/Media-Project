<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@700&display=swap" rel="stylesheet">
    <title>Video Gallery</title>
    @vite(['resources/css/app.css']) 
    @vite(['resources/css/gallery-videos.css']) 
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="menu-icon">
            <img src="{{ asset('images/menus.png') }}" alt="Menu Icon">
        </div>
        <div class="side-menu">
            <div class="close-menu">&times;</div>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/image-gallery">Images</a></li>
                <li><a href="/video-gallery">Videos</a></li>
                <li><a href="/audio-gallery">Audios</a></li>
            </ul>
        </div>
    </header>
    <!-- About Us Section with Background Video -->
    <section class="about-section">
        <video autoplay muted loop playsinline class="about-video">
            <source src="{{ asset('videos/train-pov.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="about-overlay">
            <h2 class="about-title">VIDEO GALLERY</h2>
        </div>
    </section>
    <section class="disclaimer">
        <p>
            <strong>Disclaimer:</strong> The images, audios, and video footage provided in this gallery are available for media download and use, and must be credited to <strong>BrickMMO</strong> or the photographer specified in the image caption. They cannot be used for advertising, marketing, or to imply endorsement.
        </p>
    </section>

    <section class="video-grid">
    @foreach($data as $index => $item)
        <div class="video-container" onclick="openModal({{ $index }})">
            <div class="loader"></div>
            <iframe width="250" height="150" 
                src="https://drive.google.com/file/d/{{ $item->google_id }}/preview"
                allowfullscreen 
                onload="hideLoader(this)">
            </iframe>
            <div class="overlay">
                <p>{{ $item->name }}</p>
                <div class="circle">+</div>
            </div>
        </div>
    @endforeach

    </section>
    <section class="modal" id="videoModal">
        <button class="close" onclick="closeModal()">&times;</button>
        <button class="prev" onclick="prevVideo()">&#10094;</button>
        <div class="modal-content">
        <iframe id="modalVideo" width="560" height="315" allowfullscreen></iframe>
            <div class="modal-details">
                <h2 id="modalTitle" class="modal-title"></h2>
                <p id="modalDetails"></p><br><br>
                <a id="downloadLink" class="download-btn" download>DOWNLOAD</a>
            </div>
        </div>

        <button class="next" onclick="nextVideo()">&#10095;</button>
    </section>
    <section class="contact-section">
        <div class="contact-container">
            <h2>Like to know more?</h2>
            <p class="email">thomasadam83@hotmail.com</p>
            <div class="contact-details">
                <p class="contact-title">Humber College</p>
                <p class="contact-info">North Campus, Toronto, M8V 1K8</p>
            </div>
        </div>
    </section>
    <footer class="footer-section">
        <div class="footer-container">
        <img src="{{ asset('images/logo.png') }}" alt="Footer Logo" class="footer-logo">
            <nav class="footer-nav">
                <a href="#" class="footer-link">Home</a>
                <a href="/about" class="footer-link">About</a>
                <a href="#" class="footer-link">Gallery</a>
                <a href="#" class="footer-link">Contact</a>
            </nav>
            <div class="footer-copy">© 2025 BrickMMO</div>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuIcon = document.querySelector(".menu-icon");
            const sideMenu = document.querySelector(".side-menu");
            const closeMenu = document.querySelector(".close-menu");

            menuIcon.addEventListener("click", function(event) {
                event.stopPropagation();
                sideMenu.classList.add("active");
            });

            closeMenu.addEventListener("click", function() {
                sideMenu.classList.remove("active");
            });

            document.addEventListener("click", function(event) {
                if (!sideMenu.contains(event.target) && !menuIcon.contains(event.target)) {
                    sideMenu.classList.remove("active");
                }
            });
        });
        let videos = @json($data);
        let currentIndex = 0;
        function openModal(index) {
            currentIndex = index;
            document.getElementById('modalVideo').src = `https://drive.google.com/file/d/${videos[index].google_id}/preview?autoplay=1`;
            document.getElementById('modalTitle').innerText = videos[index].name;
            document.getElementById('modalDetails').innerHTML = `
                <div class="modal-info">
                    <p><strong>Approval Status:</strong> ${videos[index].approved == 1 ? '✅ Approved' : '❌ Not Approved'}</p>
                    <p><strong>Type:</strong> ${videos[index].type}</p>
                    <p><strong>Uploaded By:</strong> ${videos[index].first} ${videos[index].last}</p>
                    <p><strong>Email:</strong> <a href="mailto:${videos[index].email}">${videos[index].email}</a></p>
                    <p><strong>Uploaded On:</strong> ${new Date(videos[index].created_at).toLocaleDateString()}</p>
                </div>
            `;

            let videoUrl = `https://drive.google.com/uc?export=download&id=${videos[index].google_id}`;
            let videoName = `${videos[index].name.replace(/\s+/g, '_')}.mp4`; 

            let downloadBtn = document.getElementById('downloadLink');
            downloadBtn.href = videoUrl;
            downloadBtn.download = videoName;

            document.getElementById('videoModal').style.display = 'flex';
        }
        function hideLoader(iframe) {
            let loader = iframe.previousElementSibling; // Get the loader div
            if (loader) loader.style.display = 'none'; // Hide the loader
            iframe.style.visibility = 'visible'; // Show the video
        }

        function closeModal() { 
            document.getElementById('modalVideo').src = ""; 
            document.getElementById('videoModal').style.display = 'none'; 
        }

        function prevVideo() { if (currentIndex > 0) openModal(currentIndex - 1); }
        function nextVideo() { if (currentIndex < videos.length - 1) openModal(currentIndex + 1); }
    </script>
</body>
</html>
