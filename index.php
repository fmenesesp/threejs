<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/three.js-master/build/three.js"></script>
	<script src="js/three.js-master/examples/js/loaders/OBJLoader.js"></script>
	<script src="js/three.js-master/examples/js/controls/OrbitControls.js"></script>


	<link type="text/css" src="css/normalize.css" />
	<link type="text/css" src="css/skeleton.css" />
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

	<title>My first three.js app</title>
	<style>
		body {
			font-family: 'Roboto', sans-serif;
			font-weight: 100;
			margin: auto;
			padding: 0;
		}

		.configurator {
			background: url(img/bg-cars-home.jpg) no-repeat;
			background-repeat: no-repeat;
			background-size: 100% 100%;
		}

		canvas {
			width: 100%;
			max-width: 1024px;
			height: 100%;
			max-height: 400px;
		}

		.max-width {
			max-width: 1024px;
			margin: auto;
		}

		.container.title h1 {
			padding: 1.5rem 0;
			background-color: #041a2f;
			font-size: 2.5rem;
			margin: auto;
			text-align: center;
			color: white;
			line-height: 1.2;
    		letter-spacing: -.1rem;
		}

		.title a {
			text-decoration: none;
			cursor: inherit;
			font-weight: 600;
		}
	</style>
</head>

<body>

	<div class="container title">
		<div class="row">
			<div class="columns twelve">
				<h1>LA NUOVA
					<a>FIAT 500</a> PUÒ ESSERE
					<a>TUA!</a>
				</h1>
			</div>
		</div>
	</div>

	<div class="container max-width">
		<div class="container configurator">
		</div>
	</div>




	<script>
		var container;
		var camera, scene, renderer, orbit;
		var mouseX = 0,
			mouseY = 0;
		var windowHalfX = jQuery(".configurator").width() / 2;
		var windowHalfY = jQuery(".configurator").height() / 2;

		init();
		animate();

		function init() {

			container = document.createElement('div');
			jQuery(".configurator").append(container);

			camera = new THREE.PerspectiveCamera(45, 1024 / 400, 1, 1000);

			scene = new THREE.Scene();

			var ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
			scene.add(ambientLight);

			var pointLight = new THREE.PointLight(0xffffff, 0.8);
			camera.add(pointLight);

			scene.add(camera); // texture

		
			scene.background = null;

			var manager = new THREE.LoadingManager();
			manager.onProgress = function (item, loaded, total) {
				console.log(item, loaded, total);
			};

			var textureLoader = new THREE.TextureLoader(manager);
			var texture = textureLoader.load('js/three.js-master/examples/textures/UV_Grid_Sm.jpg'); // model
			var onProgress = function (xhr) {
				if (xhr.lengthComputable) {
					var percentComplete = xhr.loaded / xhr.total * 100;
					console.log(Math.round(percentComplete, 2) + '% downloaded');
				}
			};
			var onError = function (xhr) {};
			var loader = new THREE.OBJLoader(manager);
			loader.load('obj/C0818121.obj', function (object) {
				object.traverse(function (child) {
					if (child instanceof THREE.Mesh) {
						child.material.map = texture;
					}
				});
				object.position.y = -15;

				scene.add(object);
			}, onProgress, onError);

			renderer = new THREE.WebGLRenderer({ alpha: true });
			renderer.setPixelRatio(window.devicePixelRatio);
			renderer.setSize(window.innerWidth, window.innerHeight);
			container.appendChild(renderer.domElement);

			renderer.setClearColor( 0x000000, 0 ); 

			window.addEventListener('resize', onWindowResize, false);

			//Camara Orbit
			
			orbit = new THREE.OrbitControls(camera, renderer.domElement);
			orbit.target = new THREE.Vector3(0, 0, 0); // set the center
			orbit.rotateSpeed = 0.3;
			
			orbit.enableZoom = true;
			//Fix Rotate only move X
			orbit.maxDistance = 120;
			orbit.minDistance = 120;
			orbit.maxPolarAngle = 1.4;
			orbit.minPolarAngle = 1.4;
		}

		function onWindowResize() {
			windowHalfX = jQuery(".configurator").width() / 2;
			windowHalfY = jQuery(".configurator").height() / 2;
			camera.aspect = jQuery(".configurator").width() / jQuery(".configurator").height();
			camera.updateProjectionMatrix();
			renderer.setSize(jQuery(".configurator").width(), jQuery(".configurator").height());
		}

		function animate() {
			requestAnimationFrame(animate);
			render();
		}

		function StartAutoPlay(){
			orbit.autoRotate = true;
		}

		function StopAutoPlay(){
			orbit.autoRotate = true;
		}

		function render() {
			orbit.update();
			camera.lookAt(scene.position);
			renderer.render(scene, camera);
		}
	</script>
</body>

</html>