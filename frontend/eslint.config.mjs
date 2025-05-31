// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'
import eslintPluginVue from 'eslint-plugin-vue'
import eslintPluginSonar from 'eslint-plugin-sonarjs'
import eslintPluginUnicorn from 'eslint-plugin-unicorn'

export default withNuxt(
    {
        plugins: {
            vue: eslintPluginVue,
            sonarjs: eslintPluginSonar,
            unicorn: eslintPluginUnicorn
        },
        rules: {
            curly: ['error', 'all'],
            'nonblock-statement-body-position': ['error', 'beside'],
            'brace-style': ['error', '1tbs', { allowSingleLine: false }],
            complexity: ['warn', { max: 13 }],
            'sonarjs/cognitive-complexity': ['warn', 17],
            'max-depth': ['warn', 4],
            'max-params': ['warn', 7],
            'no-console': 'error',
            'prefer-const': 'error',
            'sonarjs/no-identical-functions': 'warn',
            'array-callback-return': 'error',
            '@typescript-eslint/no-explicit-any': 'off',
            '@typescript-eslint/no-useless-constructor': 'off',
            'one-var': ['error', 'never'],
            'id-length': ['warn', { min: 2, exceptions: ['i', 'j', 'x', 'y'] }],
            'no-duplicate-imports': 'error',
            'no-else-return': ['error', { allowElseIf: false }],
            'unicorn/prefer-optional-catch-binding': 'error',
            'unicorn/filename-case': ['error', { case: 'kebabCase' }],
            '@typescript-eslint/no-unused-vars': ['error', {
                vars: 'all',
                args: 'after-used',
                ignoreRestSiblings: true,
                varsIgnorePattern: '^_',
            }],
            'no-warning-comments': ['warn', { terms: ['todo'], location: 'anywhere' }],
        },
    }
)
