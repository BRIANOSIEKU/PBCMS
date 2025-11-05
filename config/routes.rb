Rails.application.routes.draw do
  devise_for :users

  # Dashboards for each role
  get "dashboards/principal"
  get "dashboards/dean"
  get "dashboards/accountant"
  get "dashboards/lecturer"
  get "dashboards/student"

  # Lecturer module routes
  resources :lecturers

  # Root
  devise_scope :user do
    root to: "devise/sessions#new"
  end
end
