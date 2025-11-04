Rails.application.routes.draw do
  # Devise routes for User model
  devise_for :users

  # Wrap the root path inside Devise scope to show login page
  devise_scope :user do
    root to: "devise/sessions#new"
  end
end
