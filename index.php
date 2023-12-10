<!DOCTYPE html>
<html>

<head>
    <style>

         /* ... (estilos do CSS) ... */

        body {
            background-image: url(imgs/bg.png);
            background-size: cover;
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .bg {
            top: 25%;
            left: 25%;
            background-image: url(imgs/street.png);
            background-size: auto;
            margin: 0;
            overflow: hidden;
            display: flex;
            position: absolute;
            width: 50%;
            height: 400px;
            align-items: flex-end;
        }

        .player {
            display: flex;
            background-image: url(imgs/police.png);
            background-size: 100%;
            background-repeat: no-repeat;
            overflow: hidden;
            width: 36px;
            height: 56px;
            border-radius: 50%;
            position: absolute;
            color: #f8f8f8;
            font-size: 22px;
            padding: 6px;
        }

        .enemy {
            display: flex;
            background-image: url(imgs/bad_guy.png);
            background-size: 100%;
            background-repeat: no-repeat;
            overflow: hidden;
            width: 18px;
            height: 28px;
            border-radius: 50%;
            position: absolute;
            color: #f8f8f8;
            font-size: 22px;
            padding: 6px;
        }

        .score {
            margin-left: 25%;
            margin-top: 16%;
            background-color: #ffff;
            max-width: 50%;
            font-size: 32px;
            font-weight: bold;
        }
    </style>
</head>

<body>

     <!-- Áudio de fundo -->
     <audio id="myAudio" loop>
        <source src="sound/bg.wav" type="audio/wav">
    </audio>

    <!-- Elemento para exibir pontuação -->
    <div id="score" class="score"></div>

    <!-- Elemento de fundo (bg) com jogador (player) -->
    <div class="bg" id="bg">
        <span id="player" class="player"></span>
    </div>

    <script>
        // Pontuação inicial
        let score = 0;

        // Obtém referência para o elemento de áudio
        var audio = document.getElementById("myAudio");

        // Evento de tecla pressionada para tocar áudio quando seta para cima é pressionada
        document.addEventListener('keydown', function(event) {
            switch (event.code) {
                case 'ArrowUp':
                    audio.play();
                    break;
            }
        });

        // Obtém referências para elementos do DOM
        const bg = document.getElementById('bg');
        const player = document.getElementById('player');
        let currentPositionX = 0;
        let currentPositionY = 0;

        // Evento de tecla pressionada para movimentar jogador e verificar colisões
        document.addEventListener('keydown', function(event) {
            const bgWidth = bg.offsetWidth;
            const bgHeight = bg.offsetHeight;

            switch (event.code) {
                case 'ArrowUp':
                    if (currentPositionY >= -310) {
                        currentPositionY -= 16;
                    }
                    break;

                case 'ArrowDown':
                    if (currentPositionY <= -16) {
                        currentPositionY += 16;
                    }
                    break;

                case 'ArrowRight':
                    currentPositionX += 20;
                    currentPositionX = Math.min(currentPositionX, bgWidth - 30);
                    break;

                case 'ArrowLeft':
                    currentPositionX -= 20;
                    currentPositionX = Math.max(currentPositionX, 0);
                    break;
            }

            // Move o jogador
            player.style.transform = `translate(${currentPositionX}px, ${currentPositionY}px)`;

            // Verifica colisão com inimigos
            const enemies = document.querySelectorAll('.enemy');
            enemies.forEach(enemy => {
                if (checkCollision(player, enemy)) {
                    // Remove o inimigo
                    enemy.remove();

                    // Atualiza a pontuação
                    var scoreElement = document.getElementById("score");
                    score = score + 1;
                    var novaPontuacao = score;
                    scoreElement.innerText = "Bandidos presos: " + novaPontuacao;

                    // Toca o som de prisão
                    var audio1 = new Audio('sound/police.wav');
                    audio1.play();
                }
            });
        });

        // Função para criar um inimigo em uma posição aleatória dentro do bg
        function createEnemy() {
            const enemy = document.createElement('div');
            enemy.classList.add('enemy');
            const enemySize = 25;

            const maxX = bg.clientWidth - enemySize;
            const maxY = -250 - enemySize;

            const randomX = Math.floor(Math.random() * maxX);
            const randomY = Math.floor(Math.random() * maxY);

            // Posiciona o inimigo
            enemy.style.transform = `translate(${randomX}px, ${randomY}px)`;
            bg.appendChild(enemy);

            return enemy;
        }

        // Cria vários inimigos iniciais
        for (let i = 0; i < 4; i++) {
            createEnemy();
        }

        // Verifica colisão com inimigos a cada 100 milissegundos
        setInterval(function() {
            const enemies = document.querySelectorAll('.enemy');
            enemies.forEach(enemy => {
                if (checkCollision(player, enemy)) {
                    // Remove o inimigo
                    enemy.remove();
                }
            });
        }, 100);

        // Adiciona inimigos a cada 5000 milissegundos (5 segundos)
        setInterval(function() {
            // Toca o som de tiro
            var audio2 = new Audio('sound/shot.wav');
            audio2.play();

            // Cria um novo inimigo
            createEnemy();
        }, 5000);

        // Função para verificar colisão entre dois elementos
        function checkCollision(element1, element2) {
            const rect1 = element1.getBoundingClientRect();
            const rect2 = element2.getBoundingClientRect();

            return (
                rect1.left < rect2.right &&
                rect1.right > rect2.left &&
                rect1.top < rect2.bottom &&
                rect1.bottom > rect2.top
            );
        }
    </script>

</body>

</html>