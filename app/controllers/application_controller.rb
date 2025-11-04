class ApplicationController < ActionController::Base
  # Only allow modern browsers supporting webp images, web push, badges, import maps, CSS nesting, and CSS :has.
  allow_browser versions: :modern
  protected

def after_sign_in_path_for(resource)
  case resource.role
  when "principal"
    dashboards_principal_path
  when "academic_dean"
    dashboards_dean_path
  when "accountant"
    dashboards_accountant_path
  when "lecturer"
    dashboards_lecturer_path
  when "student"
    dashboards_student_path
  else
    root_path
  end
end
end
