require "test_helper"

class DashboardsControllerTest < ActionDispatch::IntegrationTest
  include Devise::Test::IntegrationHelpers

  setup do
    # Clean users to avoid duplicate email conflicts
    User.delete_all

    # Create role-based users
    @principal = User.create!(email: "principal_#{SecureRandom.hex(4)}@pbcms.ac.ke", password: "password123", role: "principal")
    @dean = User.create!(email: "dean_#{SecureRandom.hex(4)}@pbcms.ac.ke", password: "password123", role: "academic_dean")
    @accountant = User.create!(email: "accountant_#{SecureRandom.hex(4)}@pbcms.ac.ke", password: "password123", role: "accountant")
    @lecturer = User.create!(email: "lecturer_#{SecureRandom.hex(4)}@pbcms.ac.ke", password: "password123", role: "lecturer")
    @student = User.create!(email: "student_#{SecureRandom.hex(4)}@pbcms.ac.ke", password: "password123", role: "student")
  end

  # ✅ Works in Integration tests — no need to set @request.env
  def sign_in_user(user)
    sign_in user
  end

  test "should get principal dashboard" do
    sign_in_user(@principal)
    get dashboards_principal_path
    assert_response :success
  end

  test "should get dean dashboard" do
    sign_in_user(@dean)
    get dashboards_dean_path
    assert_response :success
  end

  test "should get accountant dashboard" do
    sign_in_user(@accountant)
    get dashboards_accountant_path
    assert_response :success
  end

  test "should get lecturer dashboard" do
    sign_in_user(@lecturer)
    get dashboards_lecturer_path
    assert_response :success
  end

  test "should get student dashboard" do
    sign_in_user(@student)
    get dashboards_student_path
    assert_response :success
  end
end
