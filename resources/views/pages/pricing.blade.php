@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="text-center mb-5">
                        @if(Auth::check() && !Auth::user()->getCurrentActiveSubscription())
                            <h1 class="mb-2" style="font-size: 36px;">Try it for free!</h1>
                            <p style="color: #6b6b6b; font-size: 17px;">Choose the plan that works best for you.</p>
                        @else
                            <h1 class="mb-2" style="font-size: 36px;">Simple, transparent pricing</h1>
                            <p style="color: #6b6b6b; font-size: 17px;">Choose the plan that works best for you.</p>
                        @endif
                    </div>

                    @include('includes.subscriptions._plans-selector')

                    <div class="mt-5 pt-4">
                        <h3 class="mb-4" style="font-size: 24px;">Common Questions</h3>
                        <div class="accordion" id="faqAccordion">
                            <div class="accord">
                                <div class="accord-header" id="headingFour">
                                    <h5 class="mb-0">
                                        <button class="accordion-button collapsed accord-header-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Do I need a credit card for the Free Plan?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body accord-body">No, you do not need a credit card to sign up for the free plan. This is a great starting point to test out the service.</div>
                                </div>
                            </div>

                            <div class="accord">
                                <div class="accord-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="accordion-button collapsed accord-header-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Why do you need my credit card for a free trial on the Basic/Premium Plan?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body accord-body">We ask for your credit card to allow your membership to continue after your free trial, should you choose not to cancel. This also allows us to reduce fraud and prevent multiple trials for one person. You can also opt in for our Free Plan - no CC required.</div>
                                </div>
                            </div>

                            <div class="accord">
                                <div class="accord-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="accordion-button collapsed accord-header-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            How does the free trial work on the Basic/Premium plan?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body accord-body">Our free trial gives you full access to our service so that you can try it out and see if it's right for you. If you enjoy it, do nothing and your membership will automatically continue. You can cancel anytime before your trial ends and you won't be charged.</div>
                                </div>
                            </div>

                            <div class="accord">
                                <div class="accord-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="accordion-button collapsed accord-header-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            What forms of payment do you accept?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body accord-body">We accept all major credit cards. We use Stripe to process all of our transactions. When you cancel your plan before the next renewal cycle, you will retain access until the end of your subscription period.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
