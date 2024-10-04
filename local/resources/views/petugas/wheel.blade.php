<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Picker Spin Wheel</title>
    <link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('/public/images/REMO-SAGA-HD.ico') }}"/>
    <style>
        body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    background-color: #f0f0f0;
    background-image:  linear-gradient(to bottom, rgba(0, 0, 0, 0.4), rgba(255, 255, 255, 0.8)),
    url("<?= asset('/public/wheel/LOGO_PIMNAS_37_UNAIR.png') ?>");
    background-size: cover;
    border-radius: 50px;
}

.container {
    text-align: center;
}

.wheel-container {
    position: relative;
    margin-bottom: 20px;
}

#wheel {
    border: 5px solid #000;
    border-radius: 50%;
}

#pointer {
    position: absolute;
    top: 50%;
    left: 100%;
    width: 0;
    height: 0;
    border-right: 70px solid black;
    border-top: 16px solid transparent;
    border-bottom: 16px solid transparent;
    transform: translate(-44%, -58%);
}

#spin-button {
    position: absolute;
    top: 45%;
    left: 45%;
    width: 75px;
    height: 75px;
    border-radius: 50%;
    border: 2px solid #fff;
    background-color: rgb(30, 27, 221);
    color: #fff;
    box-shadow: 0 5px 20px #000;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
    animation: pulse 2s infinite;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0, 0.2);
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: rgb(248, 249, 250, 0.7);
    margin: 8% auto;
    /* padding: 20px; */
    border: 1px solid #888;
    width: 100%; /* Change width to 50% for a smaller modal */
    text-align: center;
    position: relative;
    /* border-radius: 50px; */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#confetti {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}



.button-click{
    color: white; 
    font-size: 24px; 
    padding: 15px 30px; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer;
}



.logo {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 45%;
    z-index: 1000; /* Ensure it stays on top */
}

    </style>
</head>
<body>
    <img src="<?= asset('/public/wheel/LOGO_KIRI.png') ?>" class="logo" alt="logo kiri" >
    <small href="<?= url('/kelas') ?>" class="button-click" style=" position: absolute; top: 0px; right: 0px; font-size:20px" onclick="document.getElementById('submitback').submit()">Kembali</small>
    {{-- <div id="wheelname"> --}}
        <div class="container" >
            <div class="wheel-container">
                <canvas id="wheel" width="750" height="750"></canvas>
                <div id="pointer"></div>
                <button id="spin-button" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Putar</button>
            </div>
        </div>
        <div id="result-modal" class="modal">
            <div class="modal-content">
                <span id="close-modal" class="close"></span>
                <h1 id="selected-name" style="color:red;font-size: 100px"></h1>
                <small style="color:green; font-size: 10px; position: absolute; bottom: 10px; right: 10px; display:none" id="notice-saved"> Data Saved </small>
                <small style="color:red; font-size: 10px; position: absolute; bottom: 10px; right: 10px; display:none" id="notice-failed"> Failed </small>
                <canvas id="confetti"></canvas>
            </div>
        </div>
    {{-- </div> --}}
    

    

    <!--- form submit --->
    <form method="post" id="startnew" action="<?= url('/kelas/wheel') ?>">
        @csrf
        <input type="hidden" name="jwt" value="<?= $flashMessage_json ?>">
        <input type="hidden" name="idruang" value="<?= $idruang ?>">
        <input type="hidden" name="idkelompok" id="idkelompok_terpilih">
    </form>

    <form method="post" id="submitback" action="<?= url('/kelas') ?>">
        @csrf
        <input type="hidden" name="jwt" value="<?= $flashMessage_json ?>">
    </form>

    <audio id="spin-sound" src="<?= asset('/public/wheel/spin_5_2.mp3') ?>"></audio>
    <audio id="tada-sound" src="<?= asset('/public/wheel/tada.mp3') ?>"></audio>
    <audio id="boom-sound" src="<?= asset('/public/wheel/boom.mp3') ?>"></audio>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        //---- komponen penting ------
        const names = <?= $dataruang_json ?>;
        // const names = ["CHRISTOFORUS KALO BELLO PUYANGGANA", "ATHALITA SALMA RIZQULLAH PRAYITNO"];


        var terpilih = '';
        
        //-------------------------------------

        const wheelCanvas = document.getElementById("wheel");
        const ctx = wheelCanvas.getContext("2d");
        const spinButton = document.getElementById("spin-button");
        const resultModal = document.getElementById("result-modal");
        const closeModal = document.getElementById("close-modal");
        const selectedNameDiv = document.getElementById("selected-name");
        const spinSound = document.getElementById("spin-sound");
        const confettiCanvas = document.getElementById("confetti");
        const confettiCtx = confettiCanvas.getContext("2d");
        const tadaSound = document.getElementById("tada-sound");
        const boomSound = document.getElementById("boom-sound");

        const wheelRadius = wheelCanvas.width / 2;
        const arcSize = (2 * Math.PI) / names.length;
        let angleOffset = 0;
        

        function drawWheel() {
            

            var warnadasar = 233;
            // var warnadasar = 167;
            var perubahanwarna = 175 / names.length;

            names.forEach((name, index) => {
                // console.log(name.nama_ketua+" "+name.no_urut);
                warnadasar = warnadasar - perubahanwarna;
                warnapakai = 'RGB(245, '+warnadasar+', 122)';
                // warnapakai = 'RGB(23, 75, '+warnadasar+')';
                
                var namepasang = name.nama_ketua;
                var fontsize = 18;
                if(namepasang.length > 25 ){
                    fontsize = fontsize-2;
                    if(namepasang.length > 25){
                        namepasang = namepasang.substr(0, 25)+"...";
                    }
                }

                // namepasang += " ["+name.no_urut+"]";

                // console.log(namepasang+" "+namepasang.length+" "+fontsize);

                const angle = index * arcSize + angleOffset;
                ctx.beginPath();
                ctx.arc(wheelRadius, wheelRadius, wheelRadius, angle, angle + arcSize);
                ctx.lineTo(wheelRadius, wheelRadius);
                // const colorPair = colors[Math.floor(index / (names.length / colors.length))];
                ctx.fillStyle = warnapakai;
                ctx.fill();

                ctx.save();
                ctx.translate(wheelRadius, wheelRadius);
                ctx.rotate(angle + arcSize / 2);
                ctx.textAlign = "right";
                // ctx.fillStyle = "#FFF";
                ctx.fillStyle = "#000";
                ctx.font = fontsize+"px Arial";
                ctx.fillText(namepasang, wheelRadius - 10, 10);
                ctx.restore();

                ctx.beginPath();
                ctx.moveTo(wheelRadius, wheelRadius);
                ctx.lineTo(wheelRadius + wheelRadius * Math.cos(angle), wheelRadius + wheelRadius * Math.sin(angle));
                ctx.strokeStyle = "#000"; // Set color of the separator lines
                ctx.lineWidth = 0.5; // Set width of the separator lines
                ctx.stroke();
            });
        }

        function spinWheel() {
            const spinTime = 5000;
            const spinAngle = Math.random() * 360 + 360 * 5;
            const startAngle = angleOffset;
            const endAngle = angleOffset + (spinAngle * Math.PI / 180);
            const spinStartTime = new Date().getTime();

            spinSound.play(); // Play the spin sound

            function animate() {
                const currentTime = new Date().getTime();
                const timeElapsed = currentTime - spinStartTime;
                const currentAngle = easeOutCubic(timeElapsed, startAngle, endAngle - startAngle, spinTime);

                angleOffset = currentAngle % (2 * Math.PI);
                ctx.clearRect(0, 0, wheelCanvas.width, wheelCanvas.height);
                drawWheel();

                if (timeElapsed < spinTime) {
                    requestAnimationFrame(animate);
                } else {
                    spinSound.pause(); // Stop the spin sound
                    spinSound.currentTime = 0; // Reset the sound

                    tadaSound.play();
                    const selectedNameIndex = Math.floor(((2 * Math.PI - angleOffset) % (2 * Math.PI)) / arcSize);
                    const selectedName = names[selectedNameIndex];
                    selectedNameDiv.innerHTML = `${selectedName.nama_ketua}<br>${selectedName.nama_perguruan_tinggi}`;
                    resultModal.style.display = "block";

                    // console.log(selectedName);
                    terpilih = selectedName;
                    document.getElementById('idkelompok_terpilih').value = selectedName.idkelompok;
                    
                    launchConfetti(4);
                    sendDataToServer(terpilih.idkelompok);

                    setTimeout(function(){$('#startnew').submit()}, 30000);
                    
                }
            }

            animate();
        }

        function sendDataToServer(idkelompok) {
            console.log(idkelompok);
            // Send the selected name to the server
            $.ajax({
                url: "<?= url('/kelas/simpandataterpilih') ?>",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    idkelompok: idkelompok
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code = 200){
                        // console.log("Data sent successfully:", response);
                        $('#notice-saved').show();
                    }
                    else{
                        // console.error("Error sending data:", response);
                        $('#notice-failed').show();
                    }
                    // console.log("Data sent successfully:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Error sending data:", error);
                }
            });
        }

        function easeOutCubic(t, b, c, d) {
            t /= d;
            t--;
            return c * (t * t * t + 1) + b;
        }

        function launchConfetti(times) {
            if (times > 0) {
                boomSound.play();
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });

                setTimeout(() => launchConfetti(times - 1), 800); // Launch confetti every 500ms
            }
        }


        closeModal.onclick = function() {
            resultModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == resultModal) {
                resultModal.style.display = "none";
            }
        }

        window.onload = function() {
            // spinWheel();
            // console.log(names);
            var modal = document.getElementById('result-modal');
            modal.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        };

        drawWheel();
        spinButton.addEventListener("click", function(){
            spinWheel();
            $('#spin-button').hide();
        });

        
        

    </script>
</body>
</html>
