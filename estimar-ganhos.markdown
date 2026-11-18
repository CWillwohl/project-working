# Estimar ganhos

- [x] Criar worktree e branch dedicadas a partir de `origin/main`.
- [x] Persistir os dias da semana excluídos por projeto e validar a propriedade do projeto.
- [x] Calcular os dias previstos com intervalo inclusivo e atualizar a estimativa.
- [x] Adicionar a configuração expansível e remover o card de projeções fixas.
- [x] Gerar o PDF com os mesmos dados da tela.
- [x] Verificar cálculo, persistência, autorização, PDF e estados da interface.
- [x] Publicar a branch e abrir o [PR #2](https://github.com/CWillwohl/project-working/pull/2).

## Verificação

- Pest com SQLite em memória, 5 testes e 24 asserções.
- Build dos assets com `npm run build`.
