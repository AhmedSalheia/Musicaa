<div id="contact" class="block  mt-md-3 wow bounceInUp " data-wow-duration="1.3s" data-wow-delay="40ms" >
    <div class="container">

        <div class="head-start text-center mb-3">
            <h2>contact</h2>
            <p >
                If have any suggestion or problem don’t hesitate to contact us directly
                <br>
                E-mail: Prography@gmail.com

            </p>
        </div>


        <form class="container p-lg-4  mt-1  " action="/index/message/submit" method="post" id="contact">

            <div class="row">
                <div class="col-lg-6 pr-lg-0">
                    <input type="text" class="form-control form-control-lg " id="cont_name" placeholder="Full Name" name="name" style="font-size: 90%;border-width: 2px " required="">

                    <input type="email" class="form-control form-control-lg mt-2 mt-lg-1 " id="cont_email" placeholder="E-Mail" name="email" style="font-size: 90%;border-width: 2px" required="">

                    <div class="d-none d-lg-flex mt-lg-1 ">
                        <pictute><img src="/assets/images/error.svg" class="img-fluid" style="max-width: 21px" alt=""></pictute>
                        <span class="text-uppercase " style="font-size: 90% ;line-height: 2">All fields are required</span>
                    </div>
                </div>


                <div class="col-lg-6  pl-lg-1">
                    <div class="form-group">

                        <textarea class="form-control form-control-lg  mt-2 mt-lg-0" id="mass" placeholder="The Text Of The Message" rows="3" name="message" style="font-size: 94%;border-width: 2px" required=""></textarea>
                    </div>

                    <div class="d-flex d-lg-none mt-n3 mt-lg-0 ">

                        <pictute><img src="/assets/images/error.svg" class="img-fluid" style="max-width: 21px" alt=""></pictute>
                        <span class=" text-uppercase " style="font-size: 90% ;line-height: 2 ;">All fields are required</span>
                    </div>
                    <div class="input-btn">
                        <input type="submit" class="btn  text-white" style="width: 35%;background-color: #3F96D2" value="Send" name="submit">
                    </div>

                </div>

            </div>


        </form>







        </div>

    </div>
