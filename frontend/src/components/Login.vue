<template>
  <div>
    <h2>Login</h2>
    <input v-model="username" placeholder="Username" />
    <input v-model="password" type="password" placeholder="Password" />
    <button @click="login">Login</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: ''
    };
  },
  methods: {
    async login() {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: this.username, password: this.password })
      });
      const data = await res.json();
      if (data.token) {
        localStorage.setItem('token', data.token);
        alert('Login successful');
        location.reload();
      } else {
        alert('Login failed');
      }
    }
  }
};
</script>