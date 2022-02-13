# bc_challenge_lotto

- The link to the working website is at: https://bc_challenge_lotto.antsand.ca/
- To access the 649 lotto data that is currently used in this application:  https://bc_challenge_lotto.antsand.ca/data/649.csv

## This repository uses:

- Phalcon PHP as the backend framework
	> **_NOTE:_**  Phalcon Php is a C compiled framework mounted as a plugin to PHP. 
- Vue.js as the frontend framework
- SASS for styling logic and compiling to CSS  
- JSON to store API data 
- NGINX Server
- Bootstrap for CSS library

To build and deploy the project you will need these prerequisites:
- PHP 7.4 
- Phalcon PHP V 4.2 installed
- node v10.19.0
- npm 6.14.4
- sass 3.4.25

In the root folder of the project type:
```
npm install
```

This will install all the necessary dependencies to compile Vue code to js.

To compile the vue.js code type:
```
npm run dev (for development)
npm run build (for production)
```

Development build will have debug capabilities and the code will not be compressed. 
PRoduction code will be comrpessed, smaller file size and little to no debug capabilities. 

### How does it work?
#### Backend Logic:
The framework uses a Model View Controller  MVC architecture. The Models store the business logic and interact with the database, the controller handles the link and what to show for each link and the view renders the HTML page. 

An MVC structure helps in modularizing the code into different logic components. By making the code modular, it allows for easy maintainability. 
##### Backend Structure:
 The core logic of the backend is under the app folder. The folder structure is as follows:
 ```
	-> public
	-> app
			-> Controllers 
			-> Views
			-> Models
			-> Vue (frontend framework soruce file)
			-> sass
```
The controller name and actions correspond to the HTTP link.
E.g. if you want to read the API data directly:
Typing https://bc_challenge_lotto.antsand.ca/lotto/read
corresponds to the Status Controller and Read action.

##### API Data:
Files in the model's folder generally link directly to a database. Here we do not use a database. We store the data in files. The API's are stored under
```
	-> app
	-> public
		-> data
			-> 649.csv	
	-> cache (hidden - Executed during runtime)
```

###### Models
Classes in models generally correspond to a table in a relational database. Here we do not use any database. Just a file. We treat the file as a database. The Lotto Class in lotto.php located in the models directory is used to retrieve the 649.csv file, do computational logic, and return results. It is here where this class fetches the 649 data and checks and to see if any of the numbers have matched. 
```
	-> app
		->models
			-> lotto.php
```	

The cache folder stores the PHP files. The template engine used is Volt. To convert .volt files to PHP, we need a cache folder. The cache folder is hidden on the servers.

#### Frontend Structure:
	The core Vue files are stored under:
	
		-> app
			-> Vue
				lotto.js
				dashboard.vue

Though the backend takes care of all the logic and HTTP links, the frontend is rendered using Vue. 

The below image shows how Vue is rendered and how the frontend works.
![Workflow of the frontend](https://raw.githubusercontent.com/antsand/bc_challenge_lotto/master/public/img/workflow.svg)

#### Sass:
The sass code is placed under
```
		->sass.php
		-> app
			-> Sass
				_table.scss
				main.scss
```
- Each of the scss file names correspond to the Vue components. 
- main.scss imports sass plugins and complies all the scss files into one. 
- To compile the file, we need to run sass.php which will compile the \*.scss files into css.
- The css file is generated and saved in public/css/main.css
