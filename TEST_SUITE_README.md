# SuperStatsFootball - Testing Suite

Complete testing and verification tools for production deployment.

## 🧪 Available Tests

### 1. Complete Integration Test (`test_complete_integration.php`)
**START HERE!** Master dashboard that links to all test suites.
- System information display
- Quick start guide
- Links to all individual tests

**How to Run:** Visit `http://localhost/test_complete_integration.php`

---

### 2. Database Schema Test (`test_database_schema.php`)
Verifies your database matches the backend requirements.

**What it Tests:**
- ✅ All 10 required tables exist
- ✅ Correct column structure for each table
- ✅ Required indexes are in place
- ✅ Foreign key relationships
- ✅ Table row counts

**Required Setup:**
1. Open `test_database_schema.php`
2. Update lines 10-13 with your database credentials:
   ```php
   $db_host = 'localhost';
   $db_name = 'tipscom_SSF';
   $db_user = 'cpses_tuhhy2wgw';
   $db_pass = 'YOUR_PASSWORD_HERE'; // UPDATE THIS!
   ```
3. Save and run the test

**Expected Tables:**
- `users` - User authentication
- `leagues` - League information
- `teams` - Team data
- `fixtures` - Match data
- `fixture_stats` - Match statistics
- `fixture_scores` - Match scores
- `fixture_odds` - Betting odds
- `predictions` - ML predictions
- `team_ratings` - Team performance ratings
- `user_settings` - User preferences

---

### 3. Authentication Flow Test (`test_auth_flow.php`)
Tests all authentication security features.

**What it Tests:**
- ✅ CSRF token generation and validation
- ✅ Session security configuration
- ✅ Session timestamp tracking
- ✅ Configuration constants
- ✅ Helper functions
- ✅ APIClient class availability
- ✅ Required file structure
- ✅ Cookie parameters
- ✅ Authentication state checking

**No Setup Required** - Just run the test!

**Expected Results:** 10/10 tests should pass

---

### 4. API Connectivity Test (`test_api_connectivity.php`)
Tests backend API accessibility and responses.

**What it Tests:**
- ✅ API base URL reachable
- ✅ Health endpoint (if available)
- ✅ API versioning
- ✅ Login endpoint availability
- ✅ Register endpoint availability
- ✅ Refresh token endpoint
- ✅ Error response format
- ✅ API response time
- ✅ HTTPS/SSL verification
- ✅ CORS configuration

**No Setup Required** - Uses config.php settings

**Expected Results:** All tests should pass (9-10/10)

---

## 📊 Expected Results

### Production-Ready Criteria:
- **Database:** 10/10 tables exist with correct schema
- **Authentication:** 10/10 security tests pass
- **API:** 9-10/10 connectivity tests pass

### What to Do If Tests Fail:

#### Database Tests Failing
1. Run backend database migrations
2. Check MySQL/MariaDB is running
3. Verify database credentials
4. Check database user permissions

#### Authentication Tests Failing
1. Verify config.php is properly configured
2. Check PHP version (requires 7.4+)
3. Ensure session extensions are enabled
4. Check file permissions

#### API Tests Failing
1. Verify backend API is running (Railway)
2. Check API_BASE_URL in config.php
3. Verify SSL certificate is valid
4. Check CORS settings on backend
5. Test API manually with Postman

---

## 🚀 Quick Start

1. **Update Database Credentials**
   ```bash
   # Edit test_database_schema.php
   # Update lines 10-13
   ```

2. **Run Master Test**
   ```
   http://localhost/test_complete_integration.php
   ```

3. **Run Individual Tests**
   - Click each "Run Test" button
   - Review results
   - Fix any failures

4. **Verify Production Readiness**
   - All tests passing = Ready to deploy! ✅
   - Some tests failing = Fix issues first ⚠️

---

## 🔧 Troubleshooting

### "Database connection failed"
- Check MySQL/MariaDB service is running
- Verify credentials in test_database_schema.php
- Ensure database `tipscom_SSF` exists

### "Table not found"
- Run backend database migrations:
  ```bash
  cd D:\GitHub\SuperStatsFootball\backend
  python -m app.db.init_db
  ```

### "API connection failed"
- Verify backend is running on Railway
- Check API_BASE_URL in config.php
- Test API manually: https://superstatsfootball-production.up.railway.app

### "CSRF test failed"
- Check config.php has CSRF functions
- Verify session is started
- Clear browser cookies and retry

---

## 📁 Test Files

```
D:\GitHub\SuperStatsFootballw\
├── test_complete_integration.php  # Master dashboard
├── test_database_schema.php       # Database verification
├── test_auth_flow.php             # Authentication tests
├── test_api_connectivity.php      # API integration tests
└── TEST_SUITE_README.md           # This file
```

---

## ⚠️ Important Notes

1. **Do NOT commit database passwords** - Update locally only
2. **Run tests in localhost** - Not on production server
3. **Fix all failures** before deploying to production
4. **Retest after changes** to verify fixes
5. **Delete test files** after deployment (optional for security)

---

## 🎯 Production Deployment

Once all tests pass:

1. ✅ Database schema verified
2. ✅ Authentication security confirmed
3. ✅ API connectivity working
4. 🚀 **Ready to deploy to GreenGeeks!**

Follow `DEPLOYMENT_GUIDE.md` in the backend directory for deployment instructions.

---

## 📞 Support

If tests consistently fail:
1. Check backend logs (Railway dashboard)
2. Verify environment variables
3. Test API with Postman
4. Review backend DEPLOYMENT_GUIDE.md
5. Check DATABASE schema against backend models

---

**Generated:** <?php echo date('Y-m-d H:i:s'); ?>
**Version:** 1.0.0
**Status:** Production Testing Suite
