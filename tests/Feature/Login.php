<script>

import { mount } from '@vue/test-utils'
import AuthenticateLayout from '../layouts/AuthenticateLayout.vue'
import { test } from 'vitest'

test('renders a login button', async ({ expect }) => {
  const wrapper = mount(AuthenticateLayout)
  // Asegúrate de que el selector que usas aquí corresponde al botón de inicio de sesión en tu componente
  expect(wrapper.find('button.login-button').exists()).toBe(true)
})