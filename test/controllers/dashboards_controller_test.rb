require "test_helper"

class DashboardsControllerTest < ActionDispatch::IntegrationTest
  test "should get principal" do
    get dashboards_principal_url
    assert_response :success
  end

  test "should get dean" do
    get dashboards_dean_url
    assert_response :success
  end

  test "should get accountant" do
    get dashboards_accountant_url
    assert_response :success
  end

  test "should get lecturer" do
    get dashboards_lecturer_url
    assert_response :success
  end

  test "should get student" do
    get dashboards_student_url
    assert_response :success
  end
end
