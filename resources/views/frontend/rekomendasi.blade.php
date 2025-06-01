@extends('layouts.master')

@section('title', 'Home - Stonify')

@section('content')

<div class="hero"></div>

<div class="why-choose-section">
    <div class="container my-5">
        <h1 class="text-center mb-4">Rekomendasi Desain</h1>
        <div class="row">
            <!-- Editor Canvas 1 (Rekomendasi) -->
            <div class="col-md-8">
                <div class="editor-container">
                    <canvas id="recommendedCanvas" width="600" height="400"></canvas>
                </div>
            </div>
            <!-- Tools and Recommendations -->
            <div class="col-md-4">
                <div class="mb-4">
                    <h4 class="text-center">Kategori</h4>
                    <div class="recommendation-list">
                        <select id="designCategory" class="form-control mb-2">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="pagar">Pagar</option>
                            <option value="taman">Taman</option>
                        </select>
                        <select id="stoneType" class="form-control mb-2" style="display: none;">
                            <option value="" disabled selected>Pilih Batu Alam (Untuk Pagar)</option>
                            <option value="andesit">Andesit</option>
                            <option value="mozaik">Mozaik</option>
                            <option value="napoli">Napoli</option>
                            <option value="ornamen">Ornamen</option>
                            <option value="palimanan">Palimanan</option>
                            <option value="susun_sirih_templek">Susun Sirih dan Templek</option>
                        </select>
                        <button id="applyRecommendation" class="btn btn-light btn-block">Terapkan Desain</button>
                    </div>
                </div>

                <div class="manual-tools">
                    <h4 class="text-center">Tools</h4>
                    <button id="clearRecommendedCanvas" class="btn btn-danger btn-block">Clear Canvas</button>
                    <br>
                    <button id="saveRecommendedDesign" class="btn btn-success btn-block">Save Design</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h1 class="text-center mb-4">Desain Manual</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="editor-container" style="position: relative; border: 1px solid #ccc;">
                <canvas id="manualCanvas" width="600" height="400" style="cursor: crosshair;"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="manual-tools">
                <h4 class="text-center">Tools</h4>
                
                <label for="colorPicker">Pilih Warna</label>
                <input type="color" id="colorPicker" class="form-control mb-2">
                
                <label for="brushSize"></label>
                <input type="range" id="brushSize" class="form-control mb-2" min="1" max="10" value="3">

                <label for="shapePicker">Pilih Bentuk</label>
                <select id="shapePicker" class="form-control mb-2">
                    <option value="">Tanpa Bentuk</option>
                    <option value="rectangle">Persegi</option>
                    <option value="circle">Lingkaran</option>
                    <option value="line">Garis</option>
                </select>

                <label for="texturePicker">Pilih Tekstur Batu</label>
                <select id="texturePicker" class="form-control mb-2">
                    <option value="">Tanpa Tekstur</option>
                    <option value="alur1cm">Batu Alur 1CM</option>
                    <option value="alurcatur">Batu Alur Catur</option>
                    <option value="bakaran">Batu Bakaran</option>
                    <option value="palimanan">Batu Palimanan</option>
                    <option value="palm">Batu Palm</option>
                    <option value="templek">Batu Templek</option>
                    <option value="mozaik">Batu Mozaik</option>
                    <option value="napoli">Batu Napoli</option>
                </select>
                <button id="copyElement" class="btn btn-primary btn-block">Copy</button>
                <button id="clearManualCanvas" class="btn btn-danger btn-block mt-2">Clear Canvas</button>
                <button id="saveManualDesign" class="btn btn-success btn-block mt-2">Save Design</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const recommendedCanvas = document.getElementById("recommendedCanvas");
        const recommendedCtx = recommendedCanvas.getContext("2d");

        const manualCanvas = document.getElementById("manualCanvas");
        const manualCtx = manualCanvas.getContext("2d");

        const designs = {
            pagar: {
                andesit: { text: "STONIFY", image: "{{ asset('img/pagar_batu_andesit.jpg') }}" },
                mozaik: { text: "STONIFY", image: "{{ asset('img/pagar_batu_mozaik.jpg') }}" },
                napoli: { text: "STONIFY", image: "{{ asset('img/pagar_batu_napoli.jpg') }}" },
                ornamen: { text: "STONIFY", image: "{{ asset('img/pagar_batu_ornamen.jpg') }}" },
                palimanan: { text: "STONIFY", image: "{{ asset('img/pagar_batu_palimanan.jpeg') }}" },
                susun_sirih_templek: { text: "STONIFY", image: "{{ asset('img/pagat_batu_st.jpg') }}" },
            },
            taman: [
                { text: "STONIFY", image: "{{ asset('img/taman1.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman2.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman3.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman4.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman5.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman6.jpg') }}" },
                { text: "STONIFY", image: "{{ asset('img/taman7.jpg') }}" },
            ],
        };

        function applyPagarDesign(stoneType) {
            recommendedCtx.clearRect(0, 0, recommendedCanvas.width, recommendedCanvas.height);

            if (designs.pagar[stoneType]) {
                const design = designs.pagar[stoneType];
                const img = new Image();
                img.src = design.image;
                img.onload = function () {
                    recommendedCtx.drawImage(img, 0, 0, recommendedCanvas.width, recommendedCanvas.height);
                    recommendedCtx.font = "20px Arial";
                    recommendedCtx.fillStyle = "#333";
                    recommendedCtx.fillText(design.text, 50, 200);
                };
            }
        }

        function applyTamanDesign() {
            recommendedCtx.clearRect(0, 0, recommendedCanvas.width, recommendedCanvas.height);

            const randomDesign = designs.taman[Math.floor(Math.random() * designs.taman.length)];
            const img = new Image();
            img.src = randomDesign.image;
            img.onload = function () {
                recommendedCtx.drawImage(img, 0, 0, recommendedCanvas.width, recommendedCanvas.height);
                recommendedCtx.font = "20px Arial";
                recommendedCtx.fillStyle = "#333";
                recommendedCtx.fillText(randomDesign.text, 50, 200);
            };
        }

        document.getElementById("designCategory").addEventListener("change", function () {
            const category = this.value;
            document.getElementById("stoneType").style.display = category === "pagar" ? "block" : "none";
        });

        document.getElementById("applyRecommendation").addEventListener("click", function () {
            const category = document.getElementById("designCategory").value;
            const stoneType = document.getElementById("stoneType").value;

            if (category === "taman") {
                applyTamanDesign();
            } else if (category === "pagar" && stoneType) {
                applyPagarDesign(stoneType);
            } else {
                alert("Harap pilih kategori dan jenis batu alam.");
            }
        });

        document.getElementById("clearRecommendedCanvas").addEventListener("click", function () {
            recommendedCtx.clearRect(0, 0, recommendedCanvas.width, recommendedCanvas.height);
        });

        document.getElementById("saveRecommendedDesign").addEventListener("click", function () {
            const link = document.createElement("a");
            link.download = "recommended_design.png";
            link.href = recommendedCanvas.toDataURL();
            link.click();
        });

        document.getElementById("clearManualCanvas").addEventListener("click", function () {
            manualCtx.clearRect(0, 0, manualCanvas.width, manualCanvas.height);
        });

        document.getElementById("saveManualDesign").addEventListener("click", function () {
            const link = document.createElement("a");
            link.download = "manual_design.png";
            link.href = manualCanvas.toDataURL();
            link.click();
        });
    });

    
     // fungsi desain Canvas manual
    const canvas = document.getElementById("manualCanvas");
    const ctx = canvas.getContext("2d");

    // Variabel menggambar
    let isDrawing = false;
    let brushColor = document.getElementById("colorPicker").value;
    let brushSize = document.getElementById("brushSize").value;
    let brushTexture = null;
    let shapeMode = null;
    let startX, startY;
    let elements = [];
    let selectedElement = null;
    let isDragging = false;

    // Pilihan Tekstur Batu Alam
    const textures = {
    "alur1cm": "{{ asset('img/tekstur/alur1cm.jpg') }}",
    "alurcatur": "{{ asset('img/tekstur/alurcatur.jpg') }}",
    "bakaran": "{{ asset('img/tekstur/bakaran.jpg') }}",
    "palimanan": "{{ asset('img/tekstur/batu_palimanan.jpg') }}",
    "palm": "{{ asset('img/tekstur/batu_palm.jpeg') }}",
    "templek": "{{ asset('img/tekstur/batu_templek.jpg') }}",
    "mozaik": "{{ asset('img/tekstur/mozaik.jpeg') }}",
    "napoli": "{{ asset('img/tekstur/napoli.jpeg') }}"
    };

    let textureImg = new Image();

    // Fungsi mengganti tekstur
    function setBrushTexture(textureName) {
        if (textures[textureName]) {
            textureImg.src = textures[textureName];
            textureImg.onload = () => {
                brushTexture = ctx.createPattern(textureImg, "repeat");
            };
        } else {
            brushTexture = null;
        }
    }

    // Event Listener untuk Warna & Ketebalan Kuas
    document.getElementById("colorPicker").addEventListener("input", function() {
        brushColor = this.value;
    });

    document.getElementById("brushSize").addEventListener("input", function() {
        brushSize = this.value;
    });

    document.getElementById("texturePicker").addEventListener("change", function() {
        setBrushTexture(this.value);
    });

    // Event untuk memilih bentuk
    document.getElementById("shapePicker").addEventListener("change", function() {
        shapeMode = this.value || null;
    });

    // Event untuk menggambar di canvas
    canvas.addEventListener("mousedown", (e) => {
        startX = e.offsetX;
        startY = e.offsetY;
        isDrawing = true;
        isDragging = false;
        
        selectedElement = elements.find(element =>
            e.offsetX >= element.x && e.offsetX <= element.x + (element.width || element.radius * 2) &&
            e.offsetY >= element.y && e.offsetY <= element.y + (element.height || element.radius * 2)
        );

        if (selectedElement) {
            isDragging = true;
        }
    });

    canvas.addEventListener("mousemove", (e) => {
        if (isDrawing && shapeMode) {
            redrawCanvas();
            ctx.beginPath();
            ctx.lineWidth = brushSize;
            ctx.strokeStyle = brushColor;
            let width = e.offsetX - startX;
            let height = e.offsetY - startY;
            
            if (shapeMode === "rectangle") {
                ctx.strokeRect(startX, startY, width, height);
            } else if (shapeMode === "circle") {
                ctx.arc(startX, startY, Math.sqrt(width ** 2 + height ** 2), 0, Math.PI * 2);
                ctx.stroke();
            } else if (shapeMode === "line") {
                ctx.moveTo(startX, startY);
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.stroke();
            }
            ctx.closePath();
        } else if (isDragging && selectedElement) {
            selectedElement.x = e.offsetX;
            selectedElement.y = e.offsetY;
            redrawCanvas();
        }
    });

    canvas.addEventListener("mouseup", (e) => {
        if (shapeMode) {
            let width = e.offsetX - startX;
            let height = e.offsetY - startY;
            let newElement = {
                type: shapeMode,
                x: startX,
                y: startY,
                width: width,
                height: height,
                color: brushColor,
                size: brushSize,
                texture: brushTexture 
            };
            if (shapeMode === "circle") {
                newElement.radius = Math.sqrt(width ** 2 + height ** 2);
            }
            elements.push(newElement);
        }
        isDrawing = false;
        isDragging = false;
        redrawCanvas();
    });

    // Fungsi menggambar ulang elemen di canvas
    function redrawCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        elements.forEach(element => {
            ctx.beginPath();
            ctx.lineWidth = element.size;
            ctx.strokeStyle = element.color;

            if (element.texture) {
                ctx.fillStyle = element.texture; // Gunakan tekstur jika ada
            } else {
                ctx.fillStyle = element.color; // Gunakan warna jika tidak ada tekstur
            }

            if (element.type === "rectangle") {
                ctx.fillRect(element.x, element.y, element.width, element.height);
                ctx.strokeRect(element.x, element.y, element.width, element.height);
            } else if (element.type === "circle") {
                ctx.arc(element.x, element.y, element.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.stroke();
            } else if (element.type === "line") {
                ctx.moveTo(element.x, element.y);
                ctx.lineTo(element.x + element.width, element.y + element.height);
                ctx.stroke();
            } else if (element.type === "image") {
                ctx.drawImage(element.img, element.x, element.y, element.width, element.height);
            }
            ctx.closePath();
        });
    }

    // Tombol untuk copy paste elemen
    document.getElementById("copyElement").addEventListener("click", function() {
        if (selectedElement) {
            let copiedElement = { ...selectedElement, x: selectedElement.x + 20, y: selectedElement.y + 20 };
            elements.push(copiedElement);
            redrawCanvas();
        }
    });

    // Tombol untuk menghapus canvas
    document.getElementById("clearManualCanvas").addEventListener("click", function() {
        elements = [];
        redrawCanvas();
    });

    // Tombol untuk menyimpan gambar
    document.getElementById("saveManualDesign").addEventListener("click", function() {
        const image = canvas.toDataURL("image/png");
        const link = document.createElement("a");
        link.href = image;
        link.download = "manual_design.png";
        link.click();
    });

    // Event untuk memasukkan gambar ke canvas
    document.getElementById("imagePicker").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    const newElement = {
                        type: "image",
                        x: 50,
                        y: 50,
                        width: 100,
                        height: 100,
                        img: img
                    };
                    elements.push(newElement);
                    redrawCanvas();
                };
            };
            reader.readAsDataURL(file);
        }
    });
    
</script>

@endsection
