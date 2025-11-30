@extends('layouts.main')

@section('content')
    <div class="content-wrapper mt-lg-2 mt-md-2 mt-sm-0">
        <div id="name-container" style="font-family: 'Koulen', sans-serif; font-weight: 500; font-size: 55px; line-height: 3rem"> <span class="gray-text">Your</span><span id="name" style="color: #D3103E;"> AI SUPPORT</span><span class="gray-text"> pal!</span> </div>
        <p class="mx-auto mt-4" style="font-family: 'Open Sans', sans-serif; font-weight: 300; font-size: 24px;letter-spacing: -1.2px;color: #21259;max-width: 40em;">Create your own personalized chatbot for customer support. Harness the power of AI technology in your business.</p>
        <div class="d-flex justify-content-center"> <a href="{{route('register')}}" class="btn btn-custom mb-4">Create</a> </div>
        
<div class="container mb-4">
    <div class="row">
        <div class="col-lg-11 mx-auto mb-4 mt-2">
             <img src="/images/homeanima.gif" class="img-fluid shadow" style="border: 1px solid #CCC; border-radius: 15px;">
        </div>
    </div>
</div>

<div class="mt-4" style="background-color: #EEEEF0;">
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-12 mt-4">
            <nav class="nav justify-content-center">
                <a class="nav-home-link active" href="#" data-section="section1"><i class="bi bi-people"></i> Choose</a>
                <a class="nav-home-link" href="#" data-section="section2"><i class="bi bi-palette"></i> Customize</a>
                <a class="nav-home-link" href="#" data-section="section3"><i class="bi bi-envelope"></i> Manage</a>
            </nav>
        </div>
    </div>
    
    <!-- Section 1 -->
    <div class="row justify-content-center mt-4 mb-4">
        <!-- Section 1: Choose -->
        <div id="section1" class="content-section active-section col-12 mb-4">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <img src="/images/web1.png" class="img-fluid" alt="Responsive Image 1">
                </div>
                <div class="col-12 col-lg-8 text-start">
                    <h2 class="mt-4" style="color: #A4A3B1"><span style="color: #D3103E">Choose</span> Your Character</h2>
                    <p style="color: #21259">Our platform offers a variety of chatbot characters to enhance user engagement, making conversations more personal. Easily select a design that suits your style, ensuring your chatbot stands out.</p>
                     <p style="color: #21259">For a unique touch, upload your own character or image, aligning your chatbot with your brand identity. This option offers flexibility, allowing for a perfectly customized interaction experience for your audience.</p>
                </div>
            </div>
        </div>
        
        <!-- Section 2: -->
        <div id="section2" class="content-section col-12 mb-4">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <img src="/images/web2.png" class="img-fluid" alt="Responsive Image 2">
                </div>
                <div class="col-12 col-lg-8 text-start">
                    <h2 class="mt-4" style="color: #A4A3B1"><span style="color: #D3103E">Customize</span> Your Chat’s Look & Feel</h2>
                    <p style="color: #21259">Customize your chatbot to reflect your unique brand identity with our easy-to-use customization features. Adjust colors, fonts, font families, and character sizes to create a chat experience that aligns with your style.</p>
                    <p style="color: #21259">Our platform extends these customization capabilities, allowing you to not only modify basic aesthetic elements but also scale characters and more.</p>
                </div>
            </div>
        </div>
        
        <!-- Section 3: -->
        <div id="section3" class="content-section col-12 mb-4">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <img src="/images/web3.png" class="img-fluid" alt="Responsive Image 3">
                </div>
                <div class="col-12 col-lg-8 text-start">
                    <h2 class="mt-4" style="color: #A4A3B1"><span style="color: #D3103E">Manage</span> Conversations</h2>
                    <p style="color: #21259">Oversee chatbot interactions with ease using our conversational inbox. Here, you can analyze responses, streamline FAQs, and enhance your bot’s learning. It’s your control center for improving your chatbot.</p>
                   <p style="color: #21259">It functions similarly to a mailbox, designed specifically for improving your chatbot's effectiveness and user engagement.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
        
<div class="container py-5">
    <div class="row text-center g-4 justify-content-center">
        <!-- Icon 1 -->
        <div class="col-lg-4 col-md-6">
            <div class="p-4 rounded-circle circle-homepage mx-auto">
                <i class="bi bi-journal-bookmark-fill" style="color: #FFF; font-size: 2rem;"></i>
            </div>
            <h2 class="mt-3 circle-heading">Knowledge</h2>
            <p>Train your bot effectively using FAQs, plain text, or PDF documents.</p>
        </div>
        <!-- Icon 2 -->
        <div class="col-lg-4 col-md-6">
            <div class="p-4 rounded-circle circle-homepage mx-auto">
                <i class="bi bi-cpu-fill" style="color: #FFF; font-size: 2rem;"></i>
            </div>
            <h2 class="mt-3 circle-heading ">AI Model</h2>
            <p>Seamlessly switch between OpenAI models to find the perfect fit for your chatbot. </p>
        </div>
        <!-- Icon 3 -->
        <div class="col-lg-4 col-md-6">
            <div class="p-4 rounded-circle circle-homepage mx-auto">
                <i class="bi bi-eye-fill" style="color: #FFF; font-size: 2rem;"></i>
            </div>
            <h2 class="mt-3 circle-heading">Preview</h2>
            <p>Preview and interact with your chatbot to test its responses and behavior.</p>
        </div>
    </div>
    <!-- Row for the bottom 2 icons, ensuring centering -->
    <div class="row text-center g-4 justify-content-center mt-3">
        <!-- Icon 4 -->
        <div class="col-lg-4 col-md-6 d-flex justify-content-center">
            <div>
                <div class="p-4 rounded-circle circle-homepage mx-auto">
                    <i class="bi bi-code-slash" style="color: #FFF; font-size: 2rem;"></i>
                </div>
                <h2 class="mt-3 circle-heading">Easy Integration</h2>
                <p>Easily integrate the bot into your website with our embed code.</p>
            </div>
        </div>
        <!-- Icon 5 -->
        <div class="col-lg-4 col-md-6 d-flex justify-content-center">
            <div>
                <div class="p-4 rounded-circle circle-homepage mx-auto">
                    <i class="bi bi-bar-chart-fill" style="color: #FFF; font-size: 2rem;"></i>
                </div>
                <h2 class="mt-3 circle-heading">Analyze</h2>
                <p>Analyze your AI bot usage and responses.</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-2" style="background-color: #EFEFEF;">
        <div class="container mb-4 mt-4">
            <div class="row">
                <div class="col-lg-10 mx-auto mb-4">
                    <img src="/images/animated.gif" class="img-fluid" style="background-color: #EEEEEF;">
                </div>
            </div>
        </div>
</div>    
             
<div class="mt-4" style="background-color: #FFF">     
    <h1 style="color: #A4A3B1; font-family: 'Koulen', sans-serif; font-weight: 500; font-size: 55px; margin-top: 100px;">Get Started</h1>             
    <p class="mx-auto" style="font-family: 'Open Sans', sans-serif; font-weight: 300; font-size: 24px;letter-spacing: -1.2px;color: #21259;max-width: 40em;">Start for Free, no credit card needed.<br>Join now and unleash the potential of AI technology!</p>
    <div class="d-flex justify-content-center" style="margin-bottom: 100px;"> <a href="{{route('register')}}" class="btn btn-custom mb-4">Create</a> </div>
</div>
        
</div>
@endsection

@push('bottom')

    <div class="modal fade" id="characterModal" tabindex="-1" aria-labelledby="characterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Use modal-lg for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <div style="font-family: 'BerlinSansFBDemiBold', sans-serif; color: #D4103E; font-size: 18px;" class="modal-title"> Create your own AI powered Chatbot!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column" style="height: 600px;">
                    <p>Our platform enables you to create AI-powered assistants tailored to your specific needs. Here's how a chatbot created through our service can transform your user engagement:</p>
                        <ul>
                            <li><strong>E-commerce Assistance:</strong> Do your users need help navigating your products or services? Create a chatbot to offer them 24/7 assistance with product details, order tracking, and more.</li>
                            <li><strong>Customer FAQs:</strong> Equip your chatbot with answers to frequently asked questions. Whether it's about your company, policies, or services, your users will get instant responses.</li>
                            <li><strong>Technical Support:</strong> Make troubleshooting easy. Your chatbot can guide users through solving common technical issues or direct them to your support team for more complex queries.</li>
                            <li><strong>Personalized User Experience:</strong> Enhance user engagement by providing personalized recommendations and support, tailored to individual user preferences and behaviors.</li>
                        </ul>
                    <p>Start building your AI chatbot today and elevate the user experience for your customers!</p>
                </div>
            </div>
        </div>
</div>   
        
        
        
        
    </div>

    <script>
      $(function(e) {
        $('#characters img').on('click', function(e) {
          e.preventDefault();
          const $modal = $('#characterModal');
          $modal.modal('show').on('hidden.bs.modal');
        });
      });
    </script>
<script>
let currentSection = 1;
const totalSections = 3;
let sectionChangeInterval; // Define a variable to keep track of the interval

function showSection(sectionNumber) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active-section');
    });
    document.querySelectorAll('.nav-home-link').forEach(navLink => {
        navLink.classList.remove('active');
    });

    // Show the current section
    document.getElementById(`section${sectionNumber}`).classList.add('active-section');
    document.querySelector(`.nav-home-link[data-section="section${sectionNumber}"]`).classList.add('active');
}

function nextSection() {
    currentSection = currentSection >= totalSections ? 1 : currentSection + 1;
    showSection(currentSection);
}

function startSectionChangeInterval() {
    clearInterval(sectionChangeInterval); // Clear existing intfor erval
    sectionChangeInterval = setInterval(nextSection, 10000); // Start a new interval
}

document.addEventListener("DOMContentLoaded", function() {
    startSectionChangeInterval(); // Initialize the interval when the document is ready

    // Setup click event listeners for nav links
    document.querySelectorAll('.nav-home-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section').replace('section', '');
            currentSection = parseInt(sectionId);
            showSection(currentSection);
            startSectionChangeInterval(); // Restart the interval whenever a nav link is clicked
        });
    });
});
</script>

@endpush
