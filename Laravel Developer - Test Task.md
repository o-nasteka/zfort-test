Laravel Developer - Test Task
Project Overview
Build a small Laravel project where users can submit information about an actor. The app should validate input, process data with OpenAI API, save results in a database, and display submissions.
Requirements
Form Page
Show a form with two fields: email and actor description.
Add a submit button and helper text: "Please enter your first name and last name, and also provide your address."


Validation & Processing
Email and description are required and must be unique.
Send the description to OpenAI API.
API must return: First Name, Last Name, Address, Height, Weight, Gender, Age.
If First Name, Last Name, or Address is missing → show error: "Please add first name, last name, and address to your description."
Save all extracted fields into the database (email must be unique).


After Submission
On success, redirect the user to a page showing a table of all past submissions.


Table Page
Show the following fields: First Name, Address, Gender (if available), Height (if available).


API Endpoint
Create: GET /api/actors/prompt-validation
Returns JSON: { "message": "{text_prompt}" } where text_prompt is the date we use when sending to OpenAI API


Tech Notes
Use simple templates (Bootstrap or Tailwind CDN).
Vue.js optional.
Write basic tests.

