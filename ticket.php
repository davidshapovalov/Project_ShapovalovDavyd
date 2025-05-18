        <?php
            include('partials/header.php');
        ?>


            <section class="ticket-section section-padding">
                <div class="section-overlay"></div>

                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-10 mx-auto">
                            <form id="ticketForm" class="custom-form ticket-form mb-5 mb-lg-0" action="_inc/ticket-form.php" method="post" role="form">
                                <h2 class="text-center mb-4">Get started here</h2>

                                <div class="ticket-form-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <input type="text" name="ticket-form-name" id="ticket-form-name" class="form-control" placeholder="Full name" required>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-12">
                                            <input type="email" name="ticket-form-email" id="ticket-form-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email address" required>
                                        </div>
                                    </div>

                                    <input type="tel" class="form-control" name="ticket-form-phone" placeholder="Ph 085-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required="">

                                    <h6>Choose Ticket Type</h6>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="form-check form-control">
                                                <input class="form-check-input" type="radio" name="ticket-form-type" id="flexRadioDefault1" value="Early bird" required>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Early bird $120
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="form-check form-check-radio form-control">
                                                <input class="form-check-input" type="radio" name="ticket-form-type" id="flexRadioDefault2" value="Standard">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Standard $240
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <input type="number" name="ticket-form-number" id="ticket-form-number" class="form-control" placeholder="Number of Tickets" required>

                                    <textarea name="ticket-form-message" rows="3" class="form-control" id="ticket-form-message" placeholder="Additional Request"></textarea>

                                    <div class="col-lg-4 col-md-10 col-8 mx-auto">
                                        <button type="submit" class="form-control">Buy Ticket</button>
                                    </div>
                                </div>
                            </form>
                            <div id="ticket-result" class="mt-3"></div>
                    </div>
                </div>
            </section>
            <script>
                    document.getElementById("ticketForm").addEventListener("submit", function(e) {
                        e.preventDefault(); 

                        const form = e.target;
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("ticket-result").innerHTML = data;
                            if (data.includes("Thank you")) {
                                form.reset(); 
                            }
                        })
                        .catch(error => {
                            document.getElementById("ticket-result").innerHTML = "<p style='color:red;'>Ошибка при отправке формы.</p>";
                        });
                    });
            </script>

        </main>

        <?php
          include('partials/footer.php');
        ?>
