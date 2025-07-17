<template>
  <div class="login-bg">
    <!-- Background SVGs -->
    <img
      src="@/assets/images/Lines.svg"
      alt="Lines"
      class="bg-lines"
    />
    <img
      src="@/assets/images/Image.svg"
      alt="Illustration"
      class="bg-illustration"
    />
    <!-- Register Card -->
    <div class="login-card">
      <h2 class="login-title">Create Account</h2>
      <p class="login-subtitle">Register a new account</p>
      <form @submit.prevent="handleRegister">
        <div class="input-group name-group">
          <span class="icon">
            <i class="fas fa-user"></i>
          </span>
          <input
            type="text"
            v-model="name"
            placeholder="Name"
            required
          />
        </div>
        <div class="input-group email-group">
          <span class="icon">
            <i class="fas fa-envelope"></i>
          </span>
          <input
            type="email"
            v-model="email"
            placeholder="Email ID"
            required
          />
        </div>
        <div class="input-group password-group">
          <span class="icon">
            <i class="fas fa-lock"></i>
          </span>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="password"
            placeholder="Password"
            required
          />
          <span
            class="icon right"
            @click="togglePassword"
          >
            <i :class="showPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i>
          </span>
        </div>
        <div class="input-group password-group">
          <span class="icon">
            <i class="fas fa-lock"></i>
          </span>
          <input
            :type="showConfirmPassword ? 'text' : 'password'"
            v-model="confirmPassword"
            placeholder="Confirm Password"
            required
          />
          <span
            class="icon right"
            @click="toggleConfirmPassword"
          >
            <i
              :class="showConfirmPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"
            ></i>
          </span>
        </div>
        <div class="input-group role-group">
          <span class="icon">
            <i class="fas fa-user-tag"></i>
          </span>
          <select
            v-model="role"
            required
          >
            <option value="superadmin">Super Admin</option>
            <option value="organizationadmin">Organization Admin</option>
            <option value="salesperson">Salesperson</option>
            <option value="dolphinadmin">Dolphin Admin</option>
            <option value="user">User</option>
          </select>
        </div>
        <button
          class="login-btn"
          type="submit"
        >
          Register
        </button>
      </form>
      <div class="switch-auth">
        <span>Already registered?</span>
        <router-link
          to="/"
          class="switch-link"
          >Login</router-link
        >
      </div>
      <div class="footer">
        <img
          src="@/assets/images/Logo.svg"
          alt="Dolphin Logo"
          class="footer-logo"
        />
        <div class="copyright">©2025 Dolphin | All Rights Reserved</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Register',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      role: 'user',
      showPassword: false,
      showConfirmPassword: false,
    };
  },
  methods: {
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
    toggleConfirmPassword() {
      this.showConfirmPassword = !this.showConfirmPassword;
    },
    async handleRegister() {
      if (this.password !== this.confirmPassword) {
        alert('Passwords do not match!');
        return;
      }
      try {
        const response = await fetch('http://127.0.0.1:8000/api/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            name: this.name,
            email: this.email,
            password: this.password,
            role: this.role,
          }),
        });
        const data = await response.json();
        if (response.ok) {
          localStorage.setItem('userName', data.user.name);
          localStorage.setItem('userEmail', data.user.email);
          localStorage.setItem('userRole', data.user.role);
          alert('Registration successful!');
          this.$router.push('/dashboard');
        } else {
          alert('Registration failed: ' + JSON.stringify(data));
        }
      } catch (error) {
        alert('Registration error: ' + error);
      }
    },
  },
};
</script>

<style scoped>
.login-bg {
  min-height: 100vh;
  height: 100vh;
  width: 100vw;
  background: #f4f9ff;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: auto;
  position: relative;
}

.bg-lines {
  position: absolute;
  left: 0;
  top: 0;
  padding-top: 32px;
  padding-left: 32px;
  height: 38vh;
  min-width: 80px;
  max-width: 14vw;
  z-index: 0;
  pointer-events: none;
  user-select: none;
  opacity: 0.6;
}

.bg-illustration {
  position: absolute;
  right: 0;
  bottom: 0;
  padding-bottom: 32px;
  padding-right: 32px;
  height: 32vh;
  min-width: 100px;
  max-width: 16vw;
  z-index: 0;
  pointer-events: none;
  user-select: none;
  opacity: 1;
}

.login-card {
  background: #ffffff;
  box-shadow: 8px 8px 8px 2px rgba(0, 0, 0, 0.1);
  border-radius: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 77px 32px 32px 32px;
  width: 440px;
  max-width: 95vw;
  min-width: 0;
  position: relative;
  z-index: 1;
}

.login-title {
  font-family: 'Helvetica Neue LT Std', Arial, sans-serif;
  font-style: normal;
  font-weight: 500;
  font-size: 2rem;
  line-height: 2.5rem;
  letter-spacing: 0.02em;
  color: #0f0f0f;
  margin: 0 0 8px 0;
  text-align: center;
}

.login-subtitle {
  font-family: 'Helvetica Neue LT Std', Arial, sans-serif;
  font-style: normal;
  font-weight: 300;
  font-size: 1.2rem;
  line-height: 1.6rem;
  letter-spacing: 0.02em;
  color: #646464;
  margin: 0 0 32px 0;
  text-align: center;
}

form {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.input-group {
  display: flex;
  align-items: center;
  background: #f6f6f6;
  border-radius: 9px;
  margin-bottom: 24px;
  padding: 0 18px;
  width: 100%;
  max-width: 505px;
  height: 64px;
  box-sizing: border-box;
}

.input-group input {
  border: none;
  background: transparent;
  padding: 18px 12px;
  flex: 1;
  font-size: 1rem;
  outline: none;
  color: #787878;
  font-family: 'Helvetica Neue LT Std', Arial, sans-serif;
}

.input-group .icon {
  margin-right: 12px;
  display: flex;
  align-items: center;
}
.input-group .icon img {
  width: 25px;
  height: 20px;
  object-fit: contain;
}
.input-group .icon.right {
  margin-left: auto;
  margin-right: 0;
  cursor: pointer;
}

.login-btn {
  width: 100%;
  max-width: 505px;
  height: 55px;
  background: #0164a5;
  color: #fff;
  border: none;
  border-radius: 27.68px;
  font-size: 1.2rem;
  font-family: 'Helvetica Neue LT Std', Arial, sans-serif;
  font-weight: 500;
  line-height: 30px;
  letter-spacing: 0.02em;
  cursor: pointer;
  margin-bottom: 32px;
  margin-top: 8px;
  transition: background 0.2s;
}
.login-btn:hover {
  background: #1690d1;
}

.switch-auth {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
  font-size: 1rem;
  color: #787878;
  font-family: 'Helvetica Neue LT Std', Arial, sans-serif;
}
.switch-link {
  color: #0164a5;
  text-decoration: underline;
  cursor: pointer;
  font-weight: 500;
}
.switch-link:hover {
  color: #1690d1;
}

.footer {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 8px;
}
.footer-logo {
  width: 28px;
  height: 28px;
  object-fit: contain;
  margin-bottom: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
}
.copyright {
  color: #787878;
  font-size: 14px;
  font-family: 'Inter', Arial, sans-serif;
  text-align: center;
  margin-top: 4px;
}

@media (max-width: 900px) {
  .bg-lines {
    height: 22vh;
    min-width: 50px;
    max-width: 8vw;
    padding-top: 12px;
    padding-left: 12px;
    opacity: 0.3;
  }
  .bg-illustration {
    height: 16vh;
    min-width: 60px;
    max-width: 10vw;
    padding-bottom: 12px;
    padding-right: 12px;
    opacity: 0.5;
  }
}

@media (max-width: 600px) {
  .bg-lines,
  .bg-illustration {
    display: none;
  }
  .login-card {
    width: 98vw;
    padding: 32px 4vw 24px 4vw;
    border-radius: 18px;
  }
  .input-group,
  .login-btn {
    max-width: 100vw;
    width: 100%;
    min-width: 0;
    height: 24px;
  }
}
</style>
