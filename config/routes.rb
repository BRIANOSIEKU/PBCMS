Rails.application.routes.draw do
  devise_for :users

  # Dashboards for each role
  get "dashboards/principal"
  get "dashboards/dean"
  get "dashboards/accountant"
  get "dashboards/lecturer"
  get "dashboards/student"

  # When someone visits "/", go to the Devise login page
  devise_scope :user do
    root to: "devise/sessions#new"
  end
end
