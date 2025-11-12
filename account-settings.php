<?php
$pageTitle = "Account Settings - Super Stats Football";
$pageDescription = "Manage your Super Stats Football account settings";
$activePage = "account";
include 'includes/app-header.php';
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4">Account Settings</h4>

            <div class="row">
              <div class="col-md-12">
                <div class="card mb-6">
                  <!-- Account -->
                  <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-6">
                      <i class="bx bx-user-circle" style="font-size: 100px; color: #126E51;"></i>
                    </div>
                  </div>
                  <hr class="my-0" />
                  <div class="card-body">
                    <form id="formAccountSettings" method="POST">
                      <div class="row">
                        <div class="mb-6 col-md-6">
                          <label for="firstName" class="form-label">First Name</label>
                          <input class="form-control" type="text" id="firstName" name="firstName" value="John"
                            autofocus />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="lastName" class="form-label">Last Name</label>
                          <input class="form-control" type="text" name="lastName" id="lastName" value="Doe" />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" type="text" id="email" name="email" value="john.doe@example.com"
                            placeholder="john.doe@example.com" />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label class="form-label" for="phoneNumber">Phone Number</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text">UK (+44)</span>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control"
                              placeholder="202 555 0111" />
                          </div>
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="address" class="form-label">Address</label>
                          <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="state" class="form-label">State</label>
                          <input class="form-control" type="text" id="state" name="state" placeholder="London" />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="zipCode" class="form-label">Zip Code</label>
                          <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="231465"
                            maxlength="6" />
                        </div>
                        <div class="mb-6 col-md-6">
                          <label class="form-label" for="country">Country</label>
                          <select id="country" class="form-select">
                            <option value="">Select</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Spain">Spain</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                            <option value="Italy">Italy</option>
                            <option value="Netherlands">Netherlands</option>
                          </select>
                        </div>
                        <div class="mb-6 col-md-6">
                          <label for="timezone" class="form-label">Timezone</label>
                          <select id="timezone" class="form-select">
                            <option value="">Select Timezone</option>
                            <option value="GMT">GMT (Greenwich Mean Time)</option>
                            <option value="CET">CET (Central European Time)</option>
                            <option value="EST">EST (Eastern Standard Time)</option>
                            <option value="PST">PST (Pacific Standard Time)</option>
                          </select>
                        </div>
                      </div>
                      <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <!-- /Account -->
                </div>

                <!-- Deactivate Account -->
                <div class="card">
                  <h5 class="card-header">Delete Account</h5>
                  <div class="card-body">
                    <div class="mb-6 col-12 mb-0">
                      <div class="alert alert-warning">
                        <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                        <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                      </div>
                    </div>
                    <form id="formAccountDeactivation">
                      <div class="form-check mb-6">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                          id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account
                          deactivation</label>
                      </div>
                      <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                    </form>
                  </div>
                </div>
                <!--/ Deactivate Account -->
              </div>
            </div>

          </div>
          <!-- / Content -->

<?php include 'includes/app-footer.php'; ?>
