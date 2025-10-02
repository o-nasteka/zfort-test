# Laravel Developer Test Task - TODO List

## Project Overview
Build a small Laravel project where users can submit information about an actor. The app should validate input, process data with OpenAI API, save results in a database, and display submissions.

## TODO Items

### 1. Setup Environment
- [x] Clone Laradock repository
- [ ] Configure Laradock environment (.env file)
- [ ] Start Docker containers (nginx, php-fpm, mysql, redis)
- [ ] Create Laravel application inside Laradock

### 2. Database Setup
- [ ] Create actors migration with required fields
- [ ] Set up database connection
- [ ] Run migrations

### 3. Form Page Implementation
- [ ] Create form with email and actor description fields
- [ ] Add submit button and helper text
- [ ] Implement form validation (email and description required and unique)
- [ ] Style with Bootstrap/Tailwind CDN

### 4. OpenAI Integration
- [ ] Set up OpenAI API configuration
- [ ] Create service to process actor descriptions
- [ ] Extract: First Name, Last Name, Address, Height, Weight, Gender, Age
- [ ] Handle missing required fields (First Name, Last Name, Address)

### 5. Database Operations
- [ ] Save extracted fields to database
- [ ] Ensure email uniqueness
- [ ] Handle validation errors

### 6. Table Page
- [ ] Create submissions table page
- [ ] Display: First Name, Address, Gender (if available), Height (if available)
- [ ] Style table appropriately

### 7. API Endpoint
- [ ] Create GET /api/actors/prompt-validation
- [ ] Return JSON: { "message": "{text_prompt}" }
- [ ] Use date as text_prompt when sending to OpenAI API

### 8. Testing
- [ ] Write basic tests for form validation
- [ ] Write tests for OpenAI integration
- [ ] Write tests for database operations
- [ ] Write tests for API endpoint

### 9. Git Setup
- [ ] Initialize git repository
- [ ] Connect to GitHub repository: https://github.com/o-nasteka/zfort-test
- [ ] Create initial commit
- [ ] Set up proper gitflow

## Requirements Summary

### Form Page
- Two fields: email and actor description
- Submit button with helper text: "Please enter your first name and last name, and also provide your address."
- Email and description are required and must be unique

### Validation & Processing
- Send description to OpenAI API
- API must return: First Name, Last Name, Address, Height, Weight, Gender, Age
- If First Name, Last Name, or Address is missing → show error: "Please add first name, last name, and address to your description."
- Save all extracted fields into database (email must be unique)

### After Submission
- On success, redirect to table page showing all past submissions

### Table Page
- Show: First Name, Address, Gender (if available), Height (if available)

### API Endpoint
- GET /api/actors/prompt-validation
- Returns JSON: { "message": "{text_prompt}" }

### Tech Notes
- Use simple templates (Bootstrap or Tailwind CDN)
- Vue.js optional
- Write basic tests
