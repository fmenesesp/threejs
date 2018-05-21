<!DOCTYPE html>
	<html>

	<head>
		<script src="js/three.js-master/build/three.js"></script>
		<script src="js/three.js-master/examples/js/loaders/OBJLoader.js"></script>
		<script src="js/three.js-master/examples/js/controls/OrbitControls.js"></script>
		<title>My first three.js app</title>
		<style>
			body {
				margin: 0;
			}

			canvas {
				width: 100%;
				height: 100%
			}
		</style>
	</head>

	<body>
		<script>
			var container;
			var camera, scene, renderer, orbit;
			var mouseX = 0, mouseY = 0;
			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;

			init();
			animate();

			function init() {
				
				container = document.createElement('div');
				document.body.appendChild(container);

				camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 1000);
				camera.position.z = 200 //scene
				camera.position.y = 60 //scene

				scene = new THREE.Scene();
				
				var ambientLight = new THREE.AmbientLight(0xcccccc, 0.4);
				scene.add(ambientLight);

				var pointLight = new THREE.PointLight(0xffffff, 0.8);
				camera.add(pointLight);

				scene.add(camera); // texture

				
				
				var color = new THREE.Color("rgb(171%, 175%, 180%)");
				scene.background = color;

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
					object.position.y = 0;
					
					scene.add(object);
				}, onProgress, onError);

				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio(window.devicePixelRatio);
				renderer.setSize(window.innerWidth, window.innerHeight);
				container.appendChild(renderer.domElement);
				
				window.addEventListener('resize', onWindowResize, false);


				//Camara Orbit
				orbit = new THREE.OrbitControls(camera, renderer.domElement);
				orbit.target = new THREE.Vector3(0,0,0); // set the center
				orbit.rotateSpeed = 0.3;
				orbit.autoRotate = true;
				orbit.enableZoom = true;
				orbit.maxDistance = 200;

						
				

			}

				function onWindowResize() {
					windowHalfX = window.innerWidth / 2;
					windowHalfY = window.innerHeight / 2;
					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
					renderer.setSize(window.innerWidth, window.innerHeight);
				}

				function animate() {
					requestAnimationFrame(animate);
					render();
				}

				function render() {
					orbit.update();
					camera.lookAt(scene.position);
					renderer.render(scene, camera);
				}

			
		</script>
	</body>

	</html>