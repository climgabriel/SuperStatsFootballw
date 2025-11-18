# SuperStatsFootball API Quick Reference

## Authentication
```
POST /api/v1/auth/login
Body: { "username": "email@example.com", "password": "pass" }
Response: { "access_token": "...", "user": {...} }
```

## Statistics Endpoints (GET)

All return format:
```json
{
  "fixtures": [
    {
      "fixture_id": "...",
      "league_name": "...",
      "fixture_date": "2025-01-15T...",
      "home_team": "Team A",
      "away_team": "Team B",
      // endpoint-specific fields...
    }
  ],
  "pagination": { "total": 0, "limit": 50, "offset": 0 }
}
```

### 1. Goals
```
GET /api/v1/statistics/goals?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- bts_ht_yes / bts_ht_no / bts_ft_yes / bts_ft_no (Both Teams Score)
- goals_ht_u05/u15/u25, goals_ht_o05/o15/o25 (First Half)
- goals_ft_u15/u25/u35, goals_ft_o15/o25/o35 (Full Time)
- bookmaker_u25 / bookmaker_o25 (Bookmaker Odds)
- true_odds_u25 / true_odds_o25 (Fair Odds)
```

### 2. Corners
```
GET /api/v1/statistics/corners?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- corners_ht_u35/u45/u55, corners_ht_o35/o45/o55 (First Half)
- corners_ft_u65/u75/u85, corners_ft_o65/o75/o85 (Full Time)
- bookmaker_u65 / bookmaker_o65
- true_odds_u65 / true_odds_o65
```

### 3. Cards (Yellow/Red)
```
GET /api/v1/statistics/cards?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- cards_ht_u05/u15/u25, cards_ht_o05/o15/o25 (First Half)
- cards_ft_u25/u35/u45, cards_ft_o25/o35/o45 (Full Time)
- bookmaker_u25 / bookmaker_o25
- true_odds_u25 / true_odds_o25
```

### 4. Shots
```
GET /api/v1/statistics/shots?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- total_shots_ft_1 / total_shots_ft_x / total_shots_ft_2 (1x2 format)
- shots_on_target_ht_u15/u25, shots_on_target_ft_u35/u45
- bookmaker_u25 / bookmaker_o25
- true_odds_u25 / true_odds_o25
```

### 5. Fouls
```
GET /api/v1/statistics/fouls?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- faults_ht_u85/u95/u105, faults_ht_o85/o95/o105 (First Half)
- faults_ft_u155/u175/u195, faults_ft_o155/o175/o195 (Full Time)
- bookmaker_u175 / bookmaker_o175
- true_odds_u175 / true_odds_o175
```

### 6. Offsides
```
GET /api/v1/statistics/offs?days_ahead=7&league_id=1&limit=50&offset=0

Fields in fixture:
- offsides_ht_u15/u25, offsides_ht_o15/o25 (First Half)
- offsides_ft_u25/u35, offsides_ft_o25/o35 (Full Time)
- bookmaker_u25 / bookmaker_o25
- true_odds_u25 / true_odds_o25
```

### 7. Odds/1x2
```
GET /api/v1/odds/upcoming?days_ahead=7&league_id=1&limit=100&offset=0

Fields in fixture:
- home_win / draw / away_win (Match outcome probabilities)
- over_25 / under_25 (Goals probabilities)
- both_teams_score / both_teams_no_score
- bookmaker_1 / bookmaker_x / bookmaker_2 (Match odds)
- true_odds_1 / true_odds_x / true_odds_2
```

## Query Parameters

| Parameter | Type | Default | Notes |
|-----------|------|---------|-------|
| days_ahead | int | 7 | Days to look ahead for fixtures |
| league_id | int/string | null | Single ID or comma-separated (1,2,3) |
| limit | int | 50 | Results per page |
| offset | int | 0 | Pagination offset |

## Response Data Types

- **Probabilities**: Decimal 0.0-1.0 (e.g., 0.45 = 45%)
- **Odds**: String (e.g., "2.10", "1.95")
- **Percentages**: Displayed as 45.0% after client-side conversion

## Status Codes

| Code | Meaning |
|------|---------|
| 200-299 | Success |
| 400 | Bad request / Invalid parameters |
| 401 | Unauthorized / Invalid token |
| 403 | Forbidden / Insufficient permissions |
| 404 | Not found |
| 500+ | Server error |

## Headers

**Request**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {access_token}
```

**Response**
```
Content-Type: application/json
```

## Error Response Format

```json
{
  "detail": "Error message",
  "status_code": 400
}
```

## Pagination Example

Request:
```
GET /api/v1/statistics/goals?limit=50&offset=0
GET /api/v1/statistics/goals?limit=50&offset=50  // Next page
```

Response includes:
```json
{
  "pagination": {
    "total": 250,
    "limit": 50,
    "offset": 0
  }
}
```

## User Access Limits

| Role | Max Leagues | Models |
|------|-------------|--------|
| user | 5 | 1-5 (by plan) |
| admin | 10 | All |

## Prediction Models by Plan

| Plan | Models | Price |
|------|--------|-------|
| 1 (Free) | Poisson | $0 |
| 2 (Basic) | Poisson, Dixon-Coles | $9.99 |
| 3 (Standard) | + Bivariate Poisson | $19.99 |
| 4 (Premium) | + Elo Rating | $29.99 |
| 5 (Ultimate) | + Glicko/TrueSkill | $39.99 |

