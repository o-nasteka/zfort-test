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

The setup script automatically copies `.env.example` to `.env`. You only need to:

1. Add your OpenAI API key to `src/.env`:
   ```
   OPENAI_API_KEY=your_openai_api_key_here
   ```

2. Open http://localhost in your browser

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