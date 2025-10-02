# Laravel Developer - Test Task

This is a Laravel application for managing actors with AI-powered description processing.

## Features

- Actor management with CRUD operations
- AI-powered actor information extraction from descriptions
- Support for multiple AI providers (OpenAI, Anthropic, Google)
- Clean architecture following SOLID principles
- Comprehensive testing

## Quick Start

```bash
./setup.sh
```

## AI Providers

The application supports three AI providers:

### OpenAI
- **API Key**: `OPENAI_API_KEY`
- **Model**: `OPENAI_MODEL` (default: gpt-3.5-turbo)
- **Base URL**: `OPENAI_BASE_URL` (default: https://api.openai.com/v1)

### Anthropic
- **API Key**: `ANTHROPIC_API_KEY`
- **Model**: `ANTHROPIC_MODEL` (default: claude-3-sonnet-20240229)

### Google AI
- **API Key**: `GOOGLE_AI_API_KEY`
- **Model**: `GOOGLE_AI_MODEL` (default: gemini-pro)

## Environment Variables

Create a `.env` file in the `src` directory with the following variables:

```env
# Application
APP_NAME="Actor Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret

# AI Configuration
AI_DEFAULT_PROVIDER=openai

# OpenAI Configuration
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_BASE_URL=https://api.openai.com/v1

# Anthropic Configuration
ANTHROPIC_API_KEY=your_anthropic_api_key_here
ANTHROPIC_MODEL=claude-3-sonnet-20240229

# Google AI Configuration
GOOGLE_AI_API_KEY=your_google_ai_api_key_here
GOOGLE_AI_MODEL=gemini-pro
```

## Manual Installation

If you prefer to set up manually:

1. **Start the containers**:
   ```bash
   cd laradock
   docker-compose up -d nginx mysql phpmyadmin
   ```

2. **Install dependencies**:
   ```bash
   cd src
   composer install
   ```

3. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Set up storage**:
   ```bash
   php artisan storage:link
   ```

6. **Optimize application**:
   ```bash
   php artisan optimize
   ```

## Usage

### Creating an Actor

1. Navigate to the actors page
2. Click "Add New Actor"
3. Fill in the email and description
4. The system will automatically extract actor information using AI

### AI Provider Selection

You can switch between AI providers by changing the `AI_DEFAULT_PROVIDER` environment variable:

- `openai` - Uses OpenAI GPT models
- `anthropic` - Uses Anthropic Claude models  
- `google` - Uses Google Gemini models

## Testing

Run the test suite:

```bash
cd src
php artisan test
```

## Architecture

This application follows SOLID principles and implements a clean architecture pattern. See `doc/ARCHITECTURE.md` for detailed documentation.

## API Endpoints

- `GET /actors` - List all actors
- `GET /actors/create` - Show create actor form
- `POST /actors` - Store new actor
- `GET /actors/{id}` - Show specific actor
- `GET /actors/{id}/edit` - Show edit actor form
- `PUT /actors/{id}` - Update actor
- `DELETE /actors/{id}` - Delete actor